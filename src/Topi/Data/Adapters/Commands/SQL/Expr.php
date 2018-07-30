<?php
namespace Topi\Data\Adapters\Commands\SQL;

class Expr extends \Topi\Data\Adapters\Commands\Command
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
        return $this->expr;
    }
}
