<?php
namespace Tiie\Router;

/**
 * Class Group
 *
 * @package Tiie\Router
 */
class Group
{
    /**
     * @var array
     */
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->data["id"];
    }

    public function getId() : string
    {
        return $this->data["id"];
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->data["params"];
    }
}
