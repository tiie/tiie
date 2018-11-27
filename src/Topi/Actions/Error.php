<?php
namespace Elusim\Actions;

class Error extends \Elusim\Actions\Action
{
    public function action(\Elusim\Http\Request $request, array $params = array())
    {
        $response = new \Elusim\Response\Response($this);

        $error = $params['error'];

        if ($error instanceof \Elusim\Exceptions\Http\Base) {
            $response->code($error->code());
            $response->data($error->errors());

        }elseif($error instanceof \Elusim\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Elusim\Exceptions\InvalidData){
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Elusim\Router\Exceptions\ActionNotFound){
            $response->code('404');
        }elseif($error instanceof \Elusim\Exceptions\PHPErrorException){
            $response->code(500);

            $niceTrace = new \Elusim\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }elseif($error instanceof \Exception){
            $response->code(500);

            $niceTrace = new \Elusim\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }elseif($error instanceof \Error){
            $response->code(500);

            $niceTrace = new \Elusim\NiceTrace($error->getTrace());

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
