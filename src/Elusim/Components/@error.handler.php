<?php

return function(\Elusim\Components $components) {
    return new \Elusim\Errors\ErrorHandler($components->get("response"), $components->get("@log"));
};
