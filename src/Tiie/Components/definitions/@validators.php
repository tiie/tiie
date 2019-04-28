<?php

use Tiie\Components\Supervisor as Components;
use Tiie\Validators\ValidatorsManager;

return function(Components $components) {

    $validators = new ValidatorsManager(array(
        array(
            'namespace' => '\\Tiie\\Validators'
        ),
    ), $components->get('@messages'));

    return $validators;
};
