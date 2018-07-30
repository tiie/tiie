<?php
namespace Topi\Response\Engines;

class Engines implements \Topi\Response\Engines\EnginesInterface
{
    private $engines = array();

    public function register(string $name, \Topi\Response\Engines\EngineInterface $engine)
    {
        $this->engines[$name] = $engine;
    }

    public function get(string $name) : ?\Topi\Response\Engines\EngineInterface
    {
        if (array_key_exists($name, $this->engines)) {
            return $this->engines[$name];
        } else {
            throw new \Topi\Response\Exceptions\EngineNotDefined("Response engine {$name} is not defined.");
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
