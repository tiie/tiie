<?php
namespace Tiie;

define("FLAT_TRUE", "1");
define("FLAT_FALSE", "0");

use Exception;
use Tiie\Components;
use Tiie\Config;
use Tiie\Config\Finder as ConfigFinder;
use Tiie\Env;
use Tiie\Errors\Error;
use Tiie\Errors\ErrorHandlerInterface;
use Tiie\Http\Request;
use Tiie\Http\RequestCreator;
use Tiie\Response\ResponseInterface;

class App
{
    use \Tiie\Performance\TimerTrait;

    private $config;
    private $env;
    private $router;
    private $logger;
    private $performance;
    private $components;
    private $params = array(
        // output
        'output' => 'buffer',
        'outputFile' => null,

        'configDir' => "../configs",
        'env' => Env::NAME_DEVELOPMENT,
    );

    /**
     * Base request.
     */
    private $request;

    /**
     * Init Application
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

    public function run(array $params = array(), $request = null)
    {
        $this->timer()->start(__METHOD__, array(
            "REQUEST_URI" => $_SERVER["REQUEST_URI"],
        ));

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

            $this->timer()->stop();
            $this->components->get("@performance")->save();

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
            $this->config->merge(new ConfigFinder("config.{$group->name()}"));
            $this->config->merge(new ConfigFinder("config.{$group->name()}.{$this->env->get("name")}"));
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

    public function _exceptionHandler($error)
    {
        $this->_error($error);
    }

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
        $this->timer()->start(__METHOD__);

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

            $this->timer()->stop();

            echo $result['body'];

            return null;
        } elseif ($this->params['output'] == 'return') {
            foreach ($config->get('response.headers', array()) as $header => $value) {
                if (!array_key_exists($header, $result['headers'])) {
                    $result['headers'][$header] = $value;
                }
            }

            $this->timer()->stop();

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

            $this->timer()->stop();

            return $result;
        } else {
            $this->components->get("@performance")->save();

            throw new Exception("Unknown type of output '{$this->params['output']}'.");
        }
    }

    /**
     * Count and return information about accept content type, langue, charset
     * etc.
     */
    private function accept($request)
    {
        $accept = array(
            'lang' => null,
            'contentType' => null,
        );

        // lang
        if ($this->config->is('response.lang.negotiation')) {
            $lang = $request->header('Accept-Language');
            $priorities = $this->config->get('response.lang.priorities');

            if (is_null($lang)) {
                $lang = $this->config->get('response.lang.default');
            }

            $negotiator = new \Negotiation\LanguageNegotiator();
            $accept['lang'] = $negotiator
                ->getBest($lang, $priorities)
                ->getValue()
            ;

        }else{
            $accept['lang'] = $this->config->get('response.lang.default');
        }

        if (is_null($accept['lang'])) {
            throw new Exception("Can not determine Accept-Language. Please define response.lang.default at config.");
        }

        // content type
        $contentType = null;
        if ($this->config->is('response.contentType.negotiation')) {
            // jest wlaczony mechanizm negocjacji
            // pobieram naglowek Accept z zadania
            $contentType = $request->header('Accept');
            $priorities = $this->config->get('response.contentType.priorities');

            if (is_null($contentType)) {
                $contentType = $this->config->get('response.contentType.default');
            }

            // wykorzystuje zewnetrzna biblioteke do negocjacji
            $negotiator = new \Negotiation\Negotiator();
            $accept['contentType'] = $negotiator
                ->getBest($contentType, $priorities)
                ->getValue()
            ;

        }else{
            $accept['contentType'] = $this->config->get('response.contentType.default');
        }

        if (is_null($accept['contentType'])) {
            throw new Exception("Can not determine Accept. Please define response.contentType.default at config.");
        }

        return $accept;
    }

    public function component($name)
    {
        return $this->components->get($name);
    }
}
