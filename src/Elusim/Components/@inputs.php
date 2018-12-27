<?php
use Elusim\Components;
use Elusim\Data\Validators\ValidatorsManager;
use Elusim\Data\Inputs;

return function(Components $components) {
    $inputs = new Inputs($components->get("@messages"));

    return $inputs;
};
