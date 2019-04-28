<?php

use Tiie\Components\Supervisor as Components;
use Tiie\Performance\Performance;

return function(Components $components) {
    return new Performance($components->get("@config")->get("performance"));
};
