<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\Commands\Built;

class Expr extends Command
{
    /**
     * @var string
     */
    private $expr;

    function __construct(string $expr)
    {
        parent::__construct();

        $this->expr = $expr;
    }

    public function __toString() : string
    {
        return $this->expr;
    }

    public function build(array $params = array()) : Built
    {
        return new Built($this->expr);
    }
}
