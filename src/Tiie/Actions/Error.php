<?php
namespace Tiie\Actions;

class Error extends \Tiie\Actions\Action
{
    public function action(\Tiie\Http\Request $request, array $params = array())
    {
        $response = new \Tiie\Response\Response($this);

        $error = $params['error'];

        if ($error instanceof \Tiie\Exceptions\Http\Base) {
            $response->code($error->code());
            $response->data($error->errors());

        }elseif($error instanceof \Tiie\Exceptions\ValidateException){
            // todo : ValidateException zmianiam na InvalidData
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Tiie\Exceptions\InvalidData){
            $response->code('400');
            $response->data($error->errors());

        }elseif($error instanceof \Tiie\Router\Exceptions\ActionNotFound){
            $response->code('404');
        }elseif($error instanceof \Tiie\Exceptions\PHPErrorException){
            $response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));

        }elseif($error instanceof \Exception){
            $response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

            $response->data(array(
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $niceTrace->create(),
            ));
        }elseif($error instanceof \Error){
            $response->code(500);

            $niceTrace = new \Tiie\NiceTrace($error->getTrace());

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
