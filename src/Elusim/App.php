<?php
namespace Elusim;

use Elusim\Components;
use Elusim\Env;
use Elusim\Http\RequestCreator;
use Elusim\Http\Request;
use Elusim\Response\ResponseInterface;
use Elusim\Errors\Error;
use Elusim\Errors\ErrorHandlerInterface;
use Elusim\Config;

class App
{
    private $config;
    private $env;
    private $router;
    private $components;
    private $params = array(
        // env
        'env' => 'dev',

        // output
        'output' => 'buffer',
        'outputFile' => null,
    );

    /**
     * Base request.
     */
    private $request;

    function __construct(array $params = array(), Config $config = null)
    {
        $this->loadParams($params);

        $this->config = $this->initConfig($config);

        // write components
        $this->components = new Components($this->config->get('elusim.components'));
        $this->components->set('@app', $this);
        $this->components->set('@config', $this->config);

        // export components to global scope
        global $components;
        $components = $this->components;

        // env
        $this->env = new Env(isset($_SERVER) ? $_SERVER : array());
        $this->env->set('sapi', php_sapi_name());

        $this->components->set('@env', $this->env);

        // router
        $this->router = $this->components->get("@router");
    }

    public function run(array $params = array(), $request = null)
    {
        // prepare params
        $this->loadParams($params);

        // run
        set_error_handler(array($this, '_errorHandler'));
        set_exception_handler(array($this, '_exceptionHandler'));

        // Próbuje stworzyć żądanie
        try {
            if (is_null($request)) {
                $this->request = (new RequestCreator())->create($this->env);
            } else {
                $this->request = $request;
            }
        } catch (\Exception $error) {
            return $this->_error($error);
        }

        try {
            return $this->response($this->router->run($this->request), $this->request);
        } catch (\Exception $error) {
            return $this->_error($error);
        }
    }

    private function initConfig(Config $config = null)
    {
        if (is_null($config)) {
            $config = new Config();
        }

        $config->merge(__DIR__."/Config/app.php", true);

        return $config;
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
                $request = (new \Elusim\Http\RequestCreator())->create($this->env, 1);
            }

            $result = $this->components->get("@error.handler")->handle($error);

            if ($result == ErrorHandlerInterface::PROCESS_EXIT) {
                $this->response($this->components->get("@error.handler")->response($error, $request), $request);
            }
        } catch (\Exception $error) {
            // oznacza to ze nie jest mozliwe odpowiednie przekierowanie bledu
            // akcje bledu
            echo "Fatal error : \n";
            die($error);
        } catch (\Error $error) {
            // oznacza to ze nie jest mozliwe odpowiednie przekierowanie bledu
            // akcje bledu
            echo "Fatal error : \n";
            die($error);
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
        // $accept = $this->accept($request);

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

            return null;
        } elseif ($this->params['output'] == 'return') {
            foreach ($config->get('response.headers', array()) as $header => $value) {
                if (!array_key_exists($header, $result['headers'])) {
                    $result['headers'][$header] = $value;
                }
            }

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

            return $result;
        } else {
            throw new \Exception("Unknown type of output '{$this->params['output']}'.");
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
            throw new \Exception("Can not determine Accept-Language. Please define response.lang.default at config.");
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
            throw new \Exception("Can not determine Accept. Please define response.contentType.default at config.");
        }

        return $accept;
    }

    public function component($name)
    {
        return $this->components->get($name);
    }
}
