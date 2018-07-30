<?php
return function(\Topi\Components $components) {

    $config = $components->get('@config');

    return new \Topi\Paths($config->get('topi.paths', array()));
};
