<?php
namespace Tiie\Router;

class Route
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data["id"];
    }
}
