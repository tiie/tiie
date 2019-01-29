<?php
use Tiie\Components;
use Tiie\Data\Validators\ValidatorsManager;
use Tiie\Data\Inputs;

return function(Components $components) {
    $inputs = new Inputs($components->get("@messages"));

    return $inputs;
};
