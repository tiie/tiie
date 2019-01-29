<?php

return function(\Tiie\Components $components) {
    return new \Tiie\Errors\ErrorHandler($components->get("response"), $components->get("@log"));
};
