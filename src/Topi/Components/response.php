<?php

return function(\Topi\Components $components, array $params = array()) {
    $response = new \Topi\Response\Response($components->get("@config")->get("response"));
    $response->engines($components->get("@response.engines"));

    return $response;
};
