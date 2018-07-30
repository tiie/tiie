<?php
namespace Topi;

trait ComponentsTrait {
    protected function component($name, $params = array())
    {
        global $components;

        return $components->get($name, $params);
    }

    protected function components()
    {
        global $components;

        return $components;
    }
}
