<?php
namespace Topi\Actions;

class Error extends \Topi\Actions\Action
{
    public function action(\Topi\Http\Request $request, array $params = array())
    {
        $response = new \Topi\Response\Response($this);

        $error = $params['error'];

        if ($error instanceof \Topi\Exceptions\Http\Base) {
            $response->code($error->code());
            $response->data($error->errors());

        }elseif($error instanceof \Topi\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Topi\Exceptions\InvalidData){
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Topi\Router\Exceptions\ActionNotFound){
            $response->code('404');
        }elseif($error instanceof \Topi\Exceptions\PHPErrorException){
            $response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }elseif($error instanceof \Exception){
            $response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }elseif($error instanceof \Error){
            $response->code(500);

            $niceTrace = new \Topi\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }

        return $response;
    }
}
