<?php

use Tiie\Response\Engines\Json;
use Tiie\Response\Engines\Text;
use Tiie\Response\Engines\Twig;
use Tiie\Components;
use Tiie\Response\Engines\Engines;

return function(Components $components) {
    $engines = new Engines;

    $engines->register("json", new Json);
    $engines->register("text", new Text);
    $engines->register("twig", new Twig($components->get("@config")->get("twig")));

    return $engines;
};
