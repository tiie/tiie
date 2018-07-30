<?php
namespace Topi\Http;

function validate() {
    if (empty($_SERVER['SERVER_SOFTWARE'])) {
        throw new \Exception('Undefined server environment.');
    }

    if (strpos($_SERVER['SERVER_SOFTWARE'], 'PHP') !== false) {
        if (isset($_SERVER['PATH_INFO'])) {
            $uri = $_SERVER['PATH_INFO'];
        }elseif(isset($_SERVER['REQUEST_URI'])){
            $uri = $_SERVER['REQUEST_URI'];
        }else{
            throw new \Exception("Can not determine URI");
        }
    }elseif(strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false){
        $uri = $_SERVER['DOCUMENT_URI'];
    }else{
        throw new \Exception("Unsuported server environment.");
    }

    if (empty($_SERVER['REQUEST_METHOD'])) {
        throw new \Exception("HTTP is not defined.");
    }

    return new \Topi\Http\Request(
        $_SERVER['REQUEST_METHOD'],
        $uri,
        $_GET,
        $this->parseData(),
        array(
            'headers' => $this->getallheaders()
        )
    );
}
