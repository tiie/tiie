<?php
namespace Tiie\Response\Engines;

interface EnginesInterface
{
    public function register(string $name, \Tiie\Response\Engines\EngineInterface $engine);
    public function get(string $name) : ?\Tiie\Response\Engines\EngineInterface;
    public function defined(string $name) : int;
}
