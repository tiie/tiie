<?php declare(strict_types=1);

namespace Tiie\Components;

/**
 * Trait has some methods delegated from Components.
 * So use it if you need get somo component and you do like to use global and dependency injection.
 *
 * @package Tiie\Components
 */
trait ComponentsTrait
{
    /**
     * Return component with given name.
     *
     * @param string $name
     * @param array $params
     *
     * @return mixed|null
     */
    protected function getComponent(string $name, array $params = array())
    {
        global $components;

        return $components->get($name, $params);
    }

    /**
     * Return components supervisor.
     *
     * @return Supervisor
     */
    protected function getComponents() : Supervisor
    {
        global $components;

        return $components;
    }
}
