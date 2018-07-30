<?php

return function(\Topi\Components $components) {
    return new \Topi\Router\Router($components->get("@config")->get("router"));
};
