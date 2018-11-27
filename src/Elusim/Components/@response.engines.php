<?php

return function(\Elusim\Components $components) {
    $engines = new \Elusim\Response\Engines\Engines();

    $engines->register("json", new \Elusim\Response\Engines\Json());
    $engines->register("text", new \Elusim\Response\Engines\Text());
    $engines->register("twig", new \Elusim\Response\Engines\Twig());

    return $engines;
};
