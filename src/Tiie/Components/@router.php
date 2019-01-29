<?php

return function(\Tiie\Components $components) {
    return new \Tiie\Router\Router($components->get("@config")->get("router"));
};
