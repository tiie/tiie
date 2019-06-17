<?php
namespace Tiie;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

define("FLAT_TRUE", "1");
define("FLAT_FALSE", "0");

error_reporting(E_ALL);

use Exception;
use Psr\Log\LoggerInterface;
use Tiie\Components\Supervisor as Components;
use Tiie\Config\Finder as ConfigFinder;
use Tiie\Errors\Error;
use Tiie\Errors\ErrorHandlerInterface;
use Tiie\Http\Request;
use Tiie\Http\RequestCreator;
use Tiie\Performance\Performance;
use Tiie\Performance\TimerTrait;
use Tiie\Response\ResponseInterface;
use Tiie\Router\Router;

/**
 * @package Tiie
 */
class App
{
    use TimerTrait;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Env
     */
    private $env;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Performance
     */
    private $performance;

    /**
     * @var Components
     */
    private $components;

    /**
     * @var array
     */
    private $params = array(
        // output
        'output' => 'buffer',
        'outputFile' => null,

        'configDir' => "../configs",
        'env' => Env::NAME_DEVELOPMENT,
    );

    /**
     * @var Request
     */
    private $request;

    /**
     * Init application.
     *
     * @param array $params
     *
     * @throws Exception
     */
    function __construct(array $params = array())
    {
        $this->loadParams($params);

        // Env
        $this->env = new Env(isset($_SERVER) ? $_SERVER : array());
        $this->env->set('sapi', php_sapi_name());
        $this->env->set('name', $this->params["env"]);

        // Init Config
        $this->config = $this->initConfig($this->params["configDir"]);

        // Write components
        $this->components = new Components($this->config->get('components'));
        $this->components->set('@app', $this);
        $this->components->set('@config', $this->config);
        $this->components->set('@env', $this->env);

        // Export components to global scope
        global $components;
        $components = $this->components;

        // Router
        $this->router = $this->components->get("@router");

        // Logger
        $this->logger = $this->components->get("@logger");

        $this->performance = $this->components->get("@performance");
    }

    /**
     * Run application.
     *
     * @param array $params
     * @param Request|null $request
     *
     * @return mixed|void
     */
    public function run(array $params = array(), Request $request = null)
    {
        $this->components->get("@performance.timer")->start(__METHOD__);

        $this->loadParams($params);

        // Run
        set_error_handler(array($this, '_errorHandler'));
        set_exception_handler(array($this, '_exceptionHandler'));

        try {
            if (is_null($request)) {
                $this->request = (new RequestCreator($this->config->get("request")))->create($this->env);
            } else {
                $this->request = $request;
            }
        } catch (Exception $error) {
            return $this->_error($error);
        }

        try {
            $this->router->prepare($this->request);

            $this->loadMode();

            $response = $this->response($this->router->run(), $this->request);

            return $response;
        } catch (Exception $error) {
            return $this->_error($error);
        }
    }

    private function initConfig(string $dir)
    {
        // Load default config.
        $config = new Config($dir);

        $config->merge(include(__DIR__."/config.app.php"));
        $config->merge(new ConfigFinder("general"));

        // Load env config.
        $config->merge(new ConfigFinder($this->env->get("name")));

        return $config;
    }

    private function loadMode()
    {
        // Load group.
        $group = $this->router->group();

        if (!is_null($group)) {
            $this->config->merge(new ConfigFinder("config.{$group->getName()}"));
            $this->config->merge(new ConfigFinder("config.{$group->getName()}.{$this->env->get("name")}"));
        }

        // Reload services
        $this->components->reload();
    }

    private function loadParams(array $params = array())
    {
        foreach ($this->params as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->params[$key] = $params[$key];
            }
        }
    }

    /**
     * Main exception handler.
     *
     * @param mixed $error
     */
    public function _exceptionHandler($error)
    {
        $this->_error($error);
    }

    /**
     * Main error handler.
     *
     * @param $severity
     * @param $message
     * @param $file
     * @param $line
     */
    public function _errorHandler($severity, $message, $file, $line)
    {
        $this->_error(new Error($message, 0, $severity, $file, $line));
    }

    /**
     * Handle with error.
     *
     * @param mixed $error
     */
    public function _error($error)
    {
        print_r(array(
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),

            $error->getTrace()[0],
        ));

        $request = $this->request;

        try {
            if (is_null($request)) {
                // request does not exists i create new with emergency mode
                $request = (new \Tiie\Http\RequestCreator($this->config->get("request")))->create($this->env, 1);
            }

            $result = $this->components->get("@error.handler")->handle($error);

            if ($result == ErrorHandlerInterface::PROCESS_EXIT) {
                $this->response($this->components->get("@error.handler")->response($error, $request), $request);
            }
        } catch (Exception $error) {
            // oznacza to ze nie jest mozliwe odpowiednie przekierowanie bledu
            // akcje bledu
            $this->logger->emergency($error->getMessage(), $error->getTrace());
            exit("Fatal error");
        } catch (\Error $error) {
            // oznacza to ze nie jest mozliwe odpowiednie przekierowanie bledu
            // akcje bledu
            $this->logger->emergency($error->getMessage(), $error->getTrace());
            exit("Fatal error");
        }
    }

    /**
     * Processes response and return result. In case of output buffer then
     * result is print at buffer.
     *
     * @param ResponseInterface $response
     * @param Request $request
     *
     * @return mixed It is dependent from type of buffer.
     */
    private function response(ResponseInterface $response, Request $request)
    {
        $config = $this->components->get('@config');

        $result = $response->response($request);

        if ($this->params['output'] == 'buffer') {
            if (!empty($result['code'])) {
                http_response_code($result['code']);
            }

            foreach ($config->get('response.headers', array()) as $header => $value) {
                header(sprintf('%s:%s', $header, $value));
            }

            // headers
            if (!empty($result['headers'])) {
                foreach ($result['headers'] as $name => $value) {
                    header(sprintf('%s:%s', $name, $value));
                }
            }

            echo $result['body'];

            // Stop timer
            $this->components->get("@performance.timer")->stop();
            $this->components->get("@performance")->save();

            return null;
        } elseif ($this->params['output'] == 'return') {
            foreach ($config->get('response.headers', array()) as $header => $value) {
                if (!array_key_exists($header, $result['headers'])) {
                    $result['headers'][$header] = $value;
                }
            }

            // Stop timer
            $this->components->get("@performance.timer")->stop();
            $this->components->get("@performance")->save();

            return $result;
        } elseif ($this->params['output'] == 'std') {
            // todo Respose to std
            // Dorobić odpowiednie zwracanie na standardowe wyście. Wraz z
            // obsługą błędów.
            foreach ($config->get('response.headers', array()) as $header => $value) {
                if (!array_key_exists($header, $result['headers'])) {
                    $result['headers'][$header] = $value;
                }
            }

            // Stop timer
            $this->components->get("@performance.timer")->stop();
            $this->components->get("@performance")->save();

            return $result;
        } else {
            // Stop timer
            $this->components->get("@performance.timer")->stop();
            $this->components->get("@performance")->save();

            throw new Exception("Unknown type of output '{$this->params['output']}'.");
        }
    }

    public function getComponent(string $name)
    {
        return $this->components->get($name);
    }
}
