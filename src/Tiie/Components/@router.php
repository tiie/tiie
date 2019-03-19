<?php

use Tiie\Components;
use Tiie\Router\Router;

return function(Components $components) {
    return new Router($components->get("@config")->get("router"));
};
