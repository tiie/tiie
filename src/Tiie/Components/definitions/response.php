<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components, array $params = array()) {
    $response = new \Tiie\Response\Response($components->get("@config")->get("response"));
    $response->engines($components->get("@response.engines"));

    return $response;
};
