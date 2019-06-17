<?php
namespace Tiie\Data\Adapters\Commands;

class Built
{
    private $command;
    private $params;

    function __construct(string $command = null, array $params = array())
    {
        $this->command = $command;
        $this->params = $params;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        return $this->command;
    }

    public function setCommand(string $command) : void
    {
        $this->command = $command;
    }

    public function getCommand(string $command = null) : ?string
    {
        return $this->command;
    }

    public function setParam(string $name, $value) : void
    {
        $this->params[$name] = $value;
    }

    public function getParam(string $name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    public function setParams(array $params, int $merge = 1) : void
    {
        if ($merge) {
            $this->params = array_merge($this->params, $params);
        }else{
            $this->params = $params;
        }
    }

    public function getParams() : array
    {
        return $this->params;
    }
}
