<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\Commands\Built;

class Expr extends Command
{
    private $expr;

    function __construct($expr)
    {
        $this->expr = $expr;
    }

    public function __toString()
    {
        return $this->expr;
    }

    public function build(array $params = array())
    {
        $buildCommand = new Built();
        $buildCommand->command($this->expr);

        return $buildCommand;
    }
}
