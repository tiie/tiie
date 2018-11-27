<?php

return function(\Elusim\Components $components) {
    return new \Elusim\Router\Router($components->get("@config")->get("router"));
};
