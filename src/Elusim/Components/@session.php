<?php

return function(\Elusim\Components $components) {
    $factory = new \Aura\Session\SessionFactory();
    $session = $factory->newInstance($_COOKIE);

    return $session;
};
