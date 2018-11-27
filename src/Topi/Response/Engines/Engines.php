<?php
namespace Elusim\Response\Engines;

class Engines implements \Elusim\Response\Engines\EnginesInterface
{
    private $engines = array();

    public function register(string $name, \Elusim\Response\Engines\EngineInterface $engine)
    {
        $this->engines[$name] = $engine;
    }

    public function get(string $name) : ?\Elusim\Response\Engines\EngineInterface
    {
        if (array_key_exists($name, $this->engines)) {
            return $this->engines[$name];
        } else {
            throw new \Elusim\Response\Exceptions\EngineNotDefined("Response engine {$name} is not defined.");
        }
    }

    public function defined(string $name) : int
    {
        if (array_key_exists($name, $this->engines)) {
            return 1;
        } else {
            return 0;
        }
    }
}
