<?php

return function(\Tiie\Components $components) {
    $log = new \Monolog\Logger('Tiie');
    $log->pushHandler(new Monolog\Handler\StreamHandler('./your.log'));

    return $log;
};
