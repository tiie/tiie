<?php
use Tiie\Components;
use Tiie\Performance\Performance;

return function(Components $components) {
    return new Performance($components->get("@config")->get("performance"));
};
