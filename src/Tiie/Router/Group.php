<?php
namespace Tiie\Router;

class Group
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    public function name()
    {
        return $this->data["name"];
    }
}
