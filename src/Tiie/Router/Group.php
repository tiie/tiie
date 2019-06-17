<?php
namespace Tiie\Router;

class Group
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getName() : string
    {
        return $this->data["name"];
    }
}
