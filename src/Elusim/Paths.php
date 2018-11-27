<?php
namespace Elusim;

class Paths
{
    function __construct($paths = array())
    {
        $this->paths = $paths;
    }

    public function get($name)
    {
        if (!isset($this->paths[$name])) {
            throw new \Exception("Path {$name} is not defined.");
        }

        return $this->paths[$name];
    }

    public function parse($path)
    {
        foreach ($this->paths as $name => $defPath) {
            $path = str_replace('{'.$name.'}', $defPath, $path);
        }

        return $path;
    }
}
