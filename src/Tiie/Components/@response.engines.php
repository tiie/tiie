<?php

return function(\Tiie\Components $components) {
    $engines = new \Tiie\Response\Engines\Engines();

    $engines->register("json", new \Tiie\Response\Engines\Json());
    $engines->register("text", new \Tiie\Response\Engines\Text());
    $engines->register("twig", new \Tiie\Response\Engines\Twig());

    return $engines;
};
