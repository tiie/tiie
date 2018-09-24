<?php
namespace Topi\Data\Adapters\Commands\SQL;

use Topi\Data\Adapters\Commands\Command;
use Topi\Data\Adapters\Commands\BuiltCommand;

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
        $buildCommand = new BuiltCommand();
        $buildCommand->command($this->expr);

        return $buildCommand;
    }
}
