<?php

return function(\Topi\Components $components) {
    $log = new \Monolog\Logger('name');
    $log->pushHandler(new Monolog\Handler\StreamHandler('./your.log', \Monolog\Logger::WARNING));

    return $log;
};
