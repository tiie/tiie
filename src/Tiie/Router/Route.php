<?php
namespace Tiie\Router;

/**
 * Class Route
 *
 * @package Tiie\Router
 */
class Route
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $params;

    /**
     * Route constructor.
     *
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Return ID of route.
     *
     * @return string
     */
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
