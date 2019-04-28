<?php

use Tiie\Errors\ErrorHandler;
use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    return new ErrorHandler($components->get("response"), $components->get("@logger"));
};
