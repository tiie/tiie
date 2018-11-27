<?php
namespace Elusim\Components;

class Scope
{
    private $components;
    private $scope;

    function __construct(\Elusim\Components $components, string $scope)
    {
        $this->components = $components;
        $this->scope = $scope;
    }

    public function get(string $name, array $params = array())
    {
        return $this->components->get($name, $params, $this->scope);
    }
}