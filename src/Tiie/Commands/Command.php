<?php declare(strict_types=1);

namespace Tiie\Commands;

/**
 * The basic implementation of the command.
 *
 * @package Tiie\Commands
 */
class Command implements CommandInterface {

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    function __construct(string $name = null, array $params = array())
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getParams() : array
    {
        return $this->params;
    }

    public function __toString() : string
    {
        return $this->name;
    }
}
