<?php declare(strict_types=1);

namespace Tiie\Components;

use Tiie\Components\Supervisor as Components;

/**
 * Component creation space. The space is used when the component is built from
 * multiple languages and a separate detail entity is created for
 * the initiated component.
 *
 * @package Tiie\Components
 */
class Scope
{
    /**
     * @var Supervisor
     */
    private $components;

    /**
     * @var string
     */
    private $scope;

    function __construct(Components $components, string $scope)
    {
        $this->components = $components;
        $this->scope = $scope;
    }

    /**
     * Returns the component in context from space.
     *
     * @param string $name
     * @param array $params
     *
     * @return mixed
     */
    public function get(string $name, array $params = array())
    {
        return $this->components->get($name, $params, $this->scope);
    }
}
