<?php
namespace Tiie\Router;

class Route
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    public function id()
    {
        return $this->data["id"];
    }
}
