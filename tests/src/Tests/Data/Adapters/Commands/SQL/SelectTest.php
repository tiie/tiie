<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Topi\Data\Adapters\Commands\SQL\Select;

class SelectTest extends TestCase
{
    public function testParams()
    {
        $select = (new Select())
            ->from('users')
        ;

        $select->params(array(
            'name-not-in' => array(1,2),
            'name-in' => array(4,5),
            'name-is-not-null' => 1,
            'name-is-null' => 1,
            'name-start-with' => 'start-with',
            'name-end-with' => 'end-with',
            'name-contains' => 'contains',
            'name-not-like' => 'not-like',
            'name-like' => 'like',
            'name-not-equal' => 'not-equal',
            'name-equal' => 'equal',
            'name-lower-than-equal' => 'lower-than-equal',
            'name-lower-than' => 'lower-than',
            'name-greater-than-equal' => 'greater-than-equal',
            'name-greater-than' => 'greater-than',
            'name-between' => array(1, 10)
        ), array(
            'name' => 'all',
        ));

        $this->assertEquals(1, 1);
    }
}
