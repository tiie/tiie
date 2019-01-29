<?php
use Tiie\Components;
use Tiie\Data\Validators\ValidatorsManager;

return function(Components $components) {

    $validators = new ValidatorsManager(array(
        array(
            'namespace' => '\\Tiie\\Data\\Validators'
        ),
    ), $components->get('@messages'));

    return $validators;
};
