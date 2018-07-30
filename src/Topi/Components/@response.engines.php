<?php

return function(\Topi\Components $components) {
    $engines = new \Topi\Response\Engines\Engines();

    $engines->register("json", new \Topi\Response\Engines\Json());
    $engines->register("text", new \Topi\Response\Engines\Text());
    $engines->register("twig", new \Topi\Response\Engines\Twig());

    return $engines;
};
