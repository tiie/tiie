<?php

return function(\Elusim\Components $components) {
    $log = new \Monolog\Logger('Elusim');
    $log->pushHandler(new Monolog\Handler\StreamHandler('./your.log'));

    return $log;
};
