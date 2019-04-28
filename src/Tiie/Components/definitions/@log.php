<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    $log = new \Monolog\Logger('Tiie');
    $log->pushHandler(new Monolog\Handler\StreamHandler('./your.log'));

    return $log;
};
