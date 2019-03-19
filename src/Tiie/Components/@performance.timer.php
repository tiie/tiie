<?php
use Tiie\Components;

return function(Components $components) {
    return $components->get("@performance")->timer();
};
