<?php
namespace Tiie\Errors;

use ErrorException;
use Exception;
use Error;
use Psr\Log\LoggerInterface;
use Tiie\Errors\ErrorHandlerInterface;
use Tiie\Http\Request;
use Tiie\Response\ResponseInterface;
use Tiie\Errors\NiceTrace;

class ErrorHandler
{
    private $response;
    private $logger;

    function __construct(ResponseInterface $response, LoggerInterface $logger = null)
    {
        $this->response = $response;
        $this->logger = $logger;
    }

    public function handle($error)
    {
        // emergency($message, array $context = array());
        // alert($message, array $context = array());
        // critical($message, array $context = array());
        // error($message, array $context = array());
        // warning($message, array $context = array());
        // notice($message, array $context = array());
        // info($message, array $context = array());
        // debug($message, array $context = array());
        // log($level, $message, array $context = array());

        $result = ErrorHandlerInterface::PROCESS_CONTINUATION;

        if ($error instanceof ErrorException) {
            switch($error->getSeverity()) {
            case E_PARSE :
            case E_ERROR :
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
            case E_RECOVERABLE_ERROR :
            case E_USER_ERROR :
                $this->logger->error($error->getMessage(), $error->getTrace());
                $result = ErrorHandlerInterface::PROCESS_EXIT;
                break;
            case E_WARNING :
            case E_CORE_WARNING :
            case E_COMPILE_WARNING :
            case E_USER_WARNING :
                $this->logger->warning($error->getMessage(), $error->getTrace());
                break;
            case E_NOTICE :
            case E_STRICT :
            case E_DEPRECATED :
            case E_USER_NOTICE :
            case E_USER_DEPRECATED :
                $this->logger->notice($error->getMessage(), $error->getTrace());
                break;
            default:
                $this->logger->notice($error->getMessage(), $error->getTrace());
                break;
            }

        } else if ($error instanceof Exception){
            $this->logger->error($error->getMessage(), $error->getTrace());
            $result = ErrorHandlerInterface::PROCESS_EXIT;
        } else if ($error instanceof Error){
            $this->logger->error($error->getMessage(), $error->getTrace());
            $result = ErrorHandlerInterface::PROCESS_EXIT;
        }

        return $result;
    }

    public function response($error, Request $request) : \Tiie\Response\ResponseInterface
    {
        if ($error instanceof \Tiie\Exceptions\Http\Base) {
            $this->response->setCode($error->getHttpCode());
            $this->response->setData($error->getErrors());

        }else if($error instanceof \Tiie\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $this->response->setCode('400');
            $this->response->setData($error->getErrors());
        }else if($error instanceof \Tiie\Commands\Exceptions\ValidationFailed){
            $this->response->setCode('400');
            $this->response->setData($error->getErrors());

        }else if($error instanceof \Tiie\Exceptions\InvalidData){
            $this->response->setCode('400');
            $this->response->setData($error->getErrors());

        // Router
        }else if(
            $error instanceof \Tiie\Router\Exceptions\ActionNotFound ||
            $error instanceof \Tiie\Router\Exceptions\MethodNotFound ||
            $error instanceof \Tiie\Router\Exceptions\RouteNotFound
        ){
            $this->response->setCode('404');
            $this->response->setLayout('layouts/main.html');
            $this->response->setTemplate('notFound.html');
        }else if($error instanceof \Tiie\Exceptions\PHPErrorException){
            $this->response->setCode(500);

            $niceTrace = new NiceTrace($error->getTrace());

            $this->response->setData(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }else if($error instanceof \Exception){
            $this->response->setCode(500);

            $niceTrace = new NiceTrace($error->getTrace());

            $this->response->setData(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }else if($error instanceof \Error){
            $this->response->setCode(500);

            $niceTrace = new NiceTrace($error->getTrace());

            $this->response->setData(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }

        return $this->response;
    }
}
