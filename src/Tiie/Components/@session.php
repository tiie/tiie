<?php

return function(\Tiie\Components $components) {
    $factory = new \Aura\Session\SessionFactory();
    $session = $factory->newInstance($_COOKIE);

    return $session;
};
