<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    return new \Tiie\Lang\Lang($components->get("@config")->get("lang"));
};
