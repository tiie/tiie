<?php
return function(\Elusim\Components $components) {

    $config = $components->get('@config');

    return new \Elusim\Paths($config->get('elusim.paths', array()));
};
