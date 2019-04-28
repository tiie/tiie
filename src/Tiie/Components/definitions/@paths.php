<?php

use Tiie\Components\Supervisor as Components;
use Tiie\Paths\Service as PathsService;

return function(Components $components) {
    return new PathsService($components->get('@config')->get('paths', array()));
};
