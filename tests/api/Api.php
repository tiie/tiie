<?php

// Array
// (
//     [DOCUMENT_ROOT] => F:\workspace\ofiko.api\vendor\ttmdear\php-topi\tests\api
//     [REMOTE_ADDR] => ::1
//     [REMOTE_PORT] => 62807
//     [SERVER_SOFTWARE] => PHP 7.1.7 Development Server
//     [SERVER_PROTOCOL] => HTTP/1.1
//     [SERVER_NAME] => localhost
//     [SERVER_PORT] => 80
//     [REQUEST_URI] => /clients
//     [REQUEST_METHOD] => GET
//     [SCRIPT_NAME] => /index.php
//     [SCRIPT_FILENAME] => F:\workspace\ofiko.api\vendor\ttmdear\php-topi\tests\api\index.php
//     [PATH_INFO] => /clients
//     [PHP_SELF] => /index.php/clients
//     [HTTP_HOST] => localhost
//     [HTTP_CONNECTION] => keep-alive
//     [HTTP_CACHE_CONTROL] => max-age=0
//     [HTTP_UPGRADE_INSECURE_REQUESTS] => 1
//     [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36
//     [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8
//     [HTTP_ACCEPT_ENCODING] => gzip, deflate, br
//     [HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.9
//     [REQUEST_TIME_FLOAT] => 1539861818.2253
//     [REQUEST_TIME] => 1539861818
// )
class Api
{
    public function response()
    {
        $accept = $this->accept();
        $urn = $this->urn();

        $data = null;

        if ($urn == '/clients') {
            $data = include("data/clients.php");
        }

        if (is_null($data)) {
            http_response_code(404);
        } else {
            http_response_code(200);

            if($accept == 'application/json') {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($data);
            }
        }
    }

    private function accept()
    {
        if (strpos("application/json", $_SERVER['HTTP_ACCEPT']) >= 0) {
            return "application/json";
        } else if(strpos("text/html", $_SERVER['HTTP_ACCEPT']) >= 0) {
            return "text/html";
        } else {
            return null;
        }
    }

    private function urn()
    {
        return $_SERVER['PATH_INFO'];
    }
}
