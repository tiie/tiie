<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    return new \Tests\Components\Client();
};
