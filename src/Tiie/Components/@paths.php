<?php
return function(\Tiie\Components $components) {

    $config = $components->get('@config');

    return new \Tiie\Paths($config->get('tiie.paths', array()));
};
