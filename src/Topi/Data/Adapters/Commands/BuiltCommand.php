<?php
namespace Topi\Data\Adapters\Commands;

class BuiltCommand
{
    private $command, $params;

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
        $command = $this->command;

        foreach ($this->params as $name => $value) {
            $command = str_replace(":{$name}", "'{$value}'", $command);
        }

        return $command;
    }

    public function command(string $command = null)
    {
        if (is_null($command)) {
            return $this->command;
        }else{
            $this->command = $command;

            return $this;
        }
    }

    public function param(string $name, $value = null)
    {
        switch (func_num_args()) {
        case 1:
            return isset($this->params[$name]) ? $this->params[$name] : null;
        case 2:
            $this->params[$name] = $value;

            return $this;
        default:
            return null;
        }
    }

    public function params(array $params = null, $merge = 0)
    {
        if (!is_null($params)) {
            if ($merge) {
                $this->params = array_merge($this->params, $params);
            }else{
                $this->params = $params;
            }

            return $this;
        }else{
            return $this->params;
        }
    }
}
