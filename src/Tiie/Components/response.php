<?php

return function(\Tiie\Components $components, array $params = array()) {
    $response = new \Tiie\Response\Response($components->get("@config")->get("response"));
    $response->engines($components->get("@response.engines"));

    return $response;
};
