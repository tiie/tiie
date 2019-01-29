<?php
namespace Tiie\Errors;

use Tiie\Response\ResponseInterface;
use Tiie\Http\Request;
use Psr\Log\LoggerInterface;
use ErrorException;
use Exception;
use Tiie\Errors\ErrorHandlerInterface;

class ErrorHandler
{
    private $response;
    private $log;

    function __construct(ResponseInterface $response, LoggerInterface $log = null)
    {
        $this->response = $response;
        $this->log = $log;
    }

    public function handle($error)
    {
        // todo [debug] Debug to delete
        die(print_r($error, true));
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
                $this->log->error($error->getMessage());
                $result = ErrorHandlerInterface::PROCESS_EXIT;
                break;
            case E_WARNING :
            case E_CORE_WARNING :
            case E_COMPILE_WARNING :
            case E_USER_WARNING :
                $this->log->warning($error->getMessage());
                break;
            case E_NOTICE :
            case E_STRICT :
            case E_DEPRECATED :
            case E_USER_NOTICE :
            case E_USER_DEPRECATED :
                $this->log->notice($error->getMessage());
                break;
            }
        } else if ($error instanceof Exception){
            $this->log->error($error->getMessage());
            $result = ErrorHandlerInterface::PROCESS_EXIT;
        }

        return $result;
    }

    public function response($error, Request $request) : \Tiie\Response\ResponseInterface
    {
        if ($error instanceof \Tiie\Exceptions\Http\Base) {
            $this->response->code($error->code());
            $this->response->data($error->errors());

        }elseif($error instanceof \Tiie\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $this->response->code('400');
            $this->response->data($error->errors());

        }elseif($error instanceof \Tiie\Exceptions\InvalidData){
            $this->response->code('400');
            $this->response->data($error->errors());

        }elseif($error instanceof \Tiie\Router\Exceptions\ActionNotFound){
            $this->response->code('404');
        }elseif($error instanceof \Tiie\Exceptions\PHPErrorException){
            $this->response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

            $this->response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }elseif($error instanceof \Exception){
            $this->response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

            $this->response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }elseif($error instanceof \Error){
            $this->response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

            $this->response->data(array(
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
