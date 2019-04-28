<?php

use Tiie\Components\Supervisor as Components;
use Tiie\Validators\ValidatorsManager;
use Tiie\Data\Inputs;

return function(Components $components) {
    $inputs = new Inputs($components->get("@messages"));

    return $inputs;
};
