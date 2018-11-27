<?php

return function(\Elusim\Components $components, array $params = array()) {
    $response = new \Elusim\Response\Response($components->get("@config")->get("response"));
    $response->engines($components->get("@response.engines"));

    return $response;
};
