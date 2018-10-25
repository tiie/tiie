<?php
namespace Topi\Http;

class RequestCreator
{
    public function create(\Topi\Env $env, $emergency = 0)
    {
        switch ($env->get('sapi')) {
        case 'cli':
            // todo stworzyc cala obsluge zadania w przypadku gdy aplikacja
            // jest uruchamiana w trybie konsolowym. Docelowo bedzie to
            // aplikacja napisana w Symfony/Console ktora bedzie uruchamiala
            // dowolne zadanie
            break;
        default:
            $uri = null;
            $method = null;
            $type = null;
            $input = array();

            // aplikacja uruchamiana przez modul Apache, nginx, cli-serwer itp.
            if (isset($_SERVER['PATH_INFO'])) {
                $uri = $_SERVER['PATH_INFO'];
            }elseif(isset($_SERVER['REQUEST_URI'])){
                $uri = $_SERVER['REQUEST_URI'];

                $uriexploded = explode("?", $uri);

                if (count($uriexploded) > 1) {
                    $uri = $uriexploded[0];
                }
            }elseif(isset($_SERVER['DOCUMENT_URI'])){
                $uri = $_SERVER['DOCUMENT_URI'];
            }

            $uri = urldecode($uri);

            if (is_null($uri) && $emergency == 0) {
                throw new \Exception("Can not determine URI");
            }

            // method
            if (!empty($_SERVER['REQUEST_METHOD'])) {
                $method = $_SERVER['REQUEST_METHOD'];
            }

            if (is_null($method) && $emergency == 0) {
                throw new \Exception("HTTP method is not defined.");
            }

            // content type
            if (!empty($_SERVER['CONTENT_TYPE'])) {
                $input = $this->input($_SERVER['CONTENT_TYPE'], $emergency);
            }

            if (!empty($_FILES)) {
                $input = array_merge($input, $_FILES);
            }

            // headers
            $headers = array();
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }

            // domain
            $domain = null;
            if (!empty($_SERVER['SERVER_NAME'])) {
                $domain = $_SERVER['SERVER_NAME'];
            }

            if (is_null($domain) && $emergency == 0) {
                throw new \Exception("Server name is not defined.");
            }

            return new \Topi\Http\Request(
                $method,
                $uri,
                $_GET,
                $input,
                array(
                    'headers' => $headers,
                    'ip' => empty($_SERVER['REMOTE_ADDR']) ? null : $_SERVER['REMOTE_ADDR'],
                ),
                $domain,
                $emergency
            );
        }
    }

    private function input($type, $emergency = 0)
    {
        $body = trim(file_get_contents('php://input'));

        if ($emergency) {
            return array(
                'body' => $body
            );
        }else{
            $data = array();

            if (!empty($body)) {
                switch($type){
                case 'application/x-www-form-urlencoded':
                    parse_str($body, $data);
                    break;
                case 'application/json':
                    $data = json_decode($body, true);

                    if (is_null($data)) {
                        throw new \Topi\Exceptions\Http\BadRequest('Can not decode json data.');
                    }

                    break;
                default :
                    throw new \Topi\Exceptions\Http\UnsupportedMediaType();
                }
            }

            // files
            // foreach ($_FILES as $name => $file){
            //     $data[$name] = $file;
            // }

            return $data;
        }
    }
}
