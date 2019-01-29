<?php
namespace Tiie\Response\Engines;

class Engines implements \Tiie\Response\Engines\EnginesInterface
{
    private $engines = array();

    public function register(string $name, \Tiie\Response\Engines\EngineInterface $engine)
    {
        $this->engines[$name] = $engine;
    }

    public function get(string $name) : ?\Tiie\Response\Engines\EngineInterface
    {
        if (array_key_exists($name, $this->engines)) {
            return $this->engines[$name];
        } else {
            throw new \Tiie\Response\Exceptions\EngineNotDefined("Response engine {$name} is not defined.");
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
