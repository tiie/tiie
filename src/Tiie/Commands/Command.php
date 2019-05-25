<?php declare(strict_types=1);

namespace Tiie\Commands;

/**
 * The basic implementation of the command.
 *
 * @package Tiie\Commands
 */
class Command implements CommandInterface {

    private $name;
    private $params;

    function __construct(string $name = null, array $params = array())
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function params() : array
    {
        return $this->params;
    }

    public function __toString()
    {
        return $this->name;
    }
}
