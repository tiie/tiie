<?php
namespace Topi;

class App
{
    private $config;
    private $env;
    private $router;
    private $components;

    /**
     * Podstawowe żadanie. Jest tworzone na początku, może wystąpić sytuacja
     * gdy takie żądanie nie powstanie.
     */
    private $request;

    function __construct(\Topi\Config $config)
    {
        // config
        $config->merge(array(
            'response' => array(
                'headers' => array(
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Headers' => ' Cache-Control, X-Requested-With, Content-Type',

                    // Dodatkowe naglowki ktore mogą byc odczytane przez
                    // przeglądarkę
                    'Access-Control-Expose-Headers' => ' X-Rows-Number, X-Page-Size, X-Page, X-Page-Offset, X-Pages-Number',
                ),
                'engines' => array(
                    'application/json' => 'json',
                    'application/xml' => 'xml',
                    'text/html' => 'twig',
                ),
                'contentType' => array(
                    'negotiation' => 1,
                    'default' => 'application/json',
                    // 'priorities' => array('text/html; charset=UTF-8', 'application/json', 'application/xml;q=0.5'),
                    'priorities' => array(
                        'application/json',
                        'application/xml',
                        'text/html',
                    ),
                ),
                'lang' => array(
                    'negotiation' => 1,
                    'default' => 'en-US,en',
                    // 'priorities' => array('text/html; charset=UTF-8', 'application/json', 'application/xml;q=0.5'),
                    'priorities' => array(
                        'pl-PL,pl',
                        'en-US,en'
                    ),
                ),
            ),

            'topi' => array(
                'errors' => array(
                    'errorReporting' => array(
                        // List of errors to display
                        E_ERROR,
                        E_WARNING,
                        E_PARSE,
                        E_NOTICE,
                        E_CORE_ERROR,
                        E_CORE_WARNING,
                        E_COMPILE_ERROR,
                        E_COMPILE_WARNING,
                        E_USER_ERROR,
                        E_USER_WARNING,
                        E_USER_NOTICE,
                        E_STRICT,
                        E_RECOVERABLE_ERROR,
                        E_DEPRECATED,
                        E_USER_DEPRECATED,
                    ),

                    'errorReportingSilently' => true,
                ),
                'lang' => array(
                    'default' => '@lang.dictionaries.topi',
                    'dictionaries' => array(
                        '@lang.dictionaries.topi'
                    )
                ),
                'twig' => array(
                    'loader' => array(
                        './src/App/templates',
                    ),

                    // 'layouts' => array(
                    //     'main' => 'layouts/main.html'
                    // ),
                ),
                'router' => array(
                    'error' => array(
                        'action' => \Topi\Actions\Error::class
                    )
                ),
                'components' => array(
                    // 'dirs' => array(
                    //     "../src/Components"
                    // )
                ),
                'actions' => array(
                    // 'default' => array(

                    // ),
                    // 'rest' => array(
                    //     'requireParameterDescription' => true,
                    //     'requireFieldsDescription' => true,
                    // ),
                ),
                'http' => array(

                ),
            )
        ), true);

        $this->config = $config;

        // write components
        $this->components = new \Topi\Components($this->config->get('topi.components'));
        $this->components->set('@app', $this);
        $this->components->set('@config', $config);

        // export components to global scope
        global $components;
        $components = $this->components;

        // env
        $this->env = new \Topi\Env(isset($_SERVER) ? $_SERVER : array());
        $this->env->set('sapi', php_sapi_name());

        $this->components->set('@env', $this->env);

        // router
        $this->router = $this->components->get("@router");
    }

    public function run()
    {
        set_error_handler(array($this, '_errorHandler'));
        set_exception_handler(array($this, '_exceptionHandler'));

        // Próbuje stworzyć żądanie
        try {
            $this->request = (new \Topi\Http\RequestCreator())->create($this->env);
        } catch (\Exception $error) {
            $this->error($error);
        }

        try {
            $this->response($this->router->run($this->request), $this->request);
        } catch (\Exception $error) {
            $this->error($error);
        }

    }

    public function _exceptionHandler($error)
    {
        $this->error($error);
    }

    public function _errorHandler($code, $message, $file, $line)
    {
        switch($code){
        case E_ERROR :
            $error = new \Topi\Exceptions\ErrorException($message, $code, $file, $line);
            break;
        case E_WARNING :
            $error = new \Topi\Exceptions\WarningException($message, $code, $file, $line);
            break;
        case E_PARSE :
            $error = new \Topi\Exceptions\ParseException($message, $code, $file, $line);
            break;
        case E_NOTICE :
            $error = new \Topi\Exceptions\NoticeException($message, $code, $file, $line);
            break;
        case E_CORE_ERROR :
            $error = new \Topi\Exceptions\CoreErrorException($message, $code, $file, $line);
            break;
        case E_CORE_WARNING :
            $error = new \Topi\Exceptions\CoreWarningException($message, $code, $file, $line);
            break;
        case E_COMPILE_ERROR :
            $error = new \Topi\Exceptions\CompileErrorException($message, $code, $file, $line);
            break;
        case E_COMPILE_WARNING :
            $error = new \Topi\Exceptions\CoreWarningException($message, $code, $file, $line);
            break;
        case E_USER_ERROR :
            $error = new \Topi\Exceptions\UserErrorException($message, $code, $file, $line);
            break;
        case E_USER_WARNING :
            $error = new \Topi\Exceptions\UserWarningException($message, $code, $file, $line);
            break;
        case E_USER_NOTICE :
            $error = new \Topi\Exceptions\UserNoticeException($message, $code, $file, $line);
            break;
        case E_STRICT :
            $error = new \Topi\Exceptions\StrictException($message, $code, $file, $line);
            break;
        case E_RECOVERABLE_ERROR :
            $error = new \Topi\Exceptions\RecoverableErrorException($message, $code, $file, $line);
            break;
        case E_DEPRECATED :
            $error = new \Topi\Exceptions\DeprecatedException($message, $code, $file, $line);
            break;
        case E_USER_DEPRECATED :
            $error = new \Topi\Exceptions\UserDeprecatedException($message, $code, $file, $line);
            break;
        }

        $this->error($error);
    }

    public function error($error)
    {
        // check if request exitsts
        $request = $this->request;

        try {
            if (is_null($request)) {
                // request does not exists i create new with emergency mode
                $request = (new \Topi\Http\RequestCreator())->create($this->env, 1);
            }

            $this->response($this->components->get("@error.handler")->response($error, $request), $request);
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

    private function response(\Topi\Response\ResponseInterface $response, \Topi\Http\Request $request)
    {
        $config = $this->components->get('@config');
        // $accept = $this->accept($request);

        $result = $response->response($request);

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
