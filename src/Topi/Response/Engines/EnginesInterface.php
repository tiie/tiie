<?php
namespace Elusim\Response\Engines;

interface EnginesInterface
{
    public function register(string $name, \Elusim\Response\Engines\EngineInterface $engine);
    public function get(string $name) : ?\Elusim\Response\Engines\EngineInterface;
    public function defined(string $name) : int;
}
