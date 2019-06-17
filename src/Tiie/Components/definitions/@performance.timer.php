<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    return $components->get("@performance")->getTimer();
};
