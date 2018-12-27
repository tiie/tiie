<?php
use Elusim\Components;
use Elusim\Data\Validators\ValidatorsManager;

return function(Components $components) {

    $validators = new ValidatorsManager(array(
        array(
            'namespace' => '\\Elusim\\Data\\Validators'
        ),
    ), $components->get('@messages'));

    return $validators;
};
