<?php
namespace Tiie\Config;

class Finder
{
    private $path;

    function __construct(string $path)
    {
        $this->path = $path;
    }

    public function path()
    {
        return $this->path;
    }
}
