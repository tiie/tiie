<?php

use Tiie\Errors\ErrorHandler;

return function(\Tiie\Components $components) {
    return new ErrorHandler($components->get("response"), $components->get("@logger"));
};
