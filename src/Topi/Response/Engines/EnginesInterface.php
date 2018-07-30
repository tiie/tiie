<?php
namespace Topi\Response\Engines;

interface EnginesInterface
{
    public function register(string $name, \Topi\Response\Engines\EngineInterface $engine);
    public function get(string $name) : ?\Topi\Response\Engines\EngineInterface;
    public function defined(string $name) : int;
}
