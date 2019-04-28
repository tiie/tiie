<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components, $params) {
    return new \Tiie\Data\Input($params['input']);
};
