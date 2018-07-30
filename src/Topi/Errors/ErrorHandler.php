<?php
namespace Topi\Errors;

class ErrorHandler
{
    private $response;

    function __construct(\Topi\Response\ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function handle($error)
    {

    }

    public function response($error, \Topi\Http\Request $request) : \Topi\Response\ResponseInterface
    {
        // todo : delete
        die(print_r($error, true));
        // endtodo
        if ($error instanceof \Topi\Exceptions\Http\Base) {
            $this->response->code($error->code());
            $this->response->data($error->errors());

        }elseif($error instanceof \Topi\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $this->response->code('400');
            $this->response->data($error->errors());

        }elseif($error instanceof \Topi\Exceptions\InvalidData){
            $this->response->code('400');
            $this->response->data($error->errors());

        }elseif($error instanceof \Topi\Router\Exceptions\ActionNotFound){
            $this->response->code('404');
        }elseif($error instanceof \Topi\Exceptions\PHPErrorException){
            $this->response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

            $this->response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }elseif($error instanceof \Exception){
            $this->response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

            $this->response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }elseif($error instanceof \Error){
            $this->response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

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
