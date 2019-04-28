<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    $factory = new \Aura\Session\SessionFactory();
    $session = $factory->newInstance($_COOKIE);

    return $session;
};
