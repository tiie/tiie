<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Insert;
use Tiie\Data\Adapters\Commands\SQL\Expr;
use Tiie\Data\Adapters\Commands\SQL\Update;
use Tiie\Data\Adapters\Commands\SQL\Delete;
use Tiie\Data\Adapters\Commands\Built;

class DeleteTest extends TestCase
{
    public function testFrom()
    {
        $delete = new Delete();


        // mock
        $this->assertEquals(1,1);
    }

    //
    // todo Testowanie method Where w Delete.
    // public function brackets($function)
    // public function ando()
    // public function oro()
    // public function in($column, $value)
    // public function notIn($column, $value)
    // public function isNull($column)
    // public function isNotNull($column)
    // public function startWith($column, $value)
    // public function endWith($column, $value)
    // public function contains($column, $value)
    // public function like($column, $value)
    // public function conditions($column, $conditions, array $params = array())
    // public function eq($column, $value)
    // public function neq($column, $value)
    // public function lt($column, $value)
    // public function lte($column, $value)
    // public function gt($column, $value)
    // public function gte($column, $value)
    // public function expr($expr)
    // public function exists($value)
    // public function notExists($value)
    // public function between($column, $begin, $end)

    // public function build(array $params = array())
}
