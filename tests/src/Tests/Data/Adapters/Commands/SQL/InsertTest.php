<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Topi\Data\Adapters\Commands\SQL\Select;
use Topi\Data\Adapters\Commands\SQL\Insert;
use Topi\Data\Adapters\Commands\SQL\Expr;

// $this->createVariable('variable-38', $rows);
class SelectTest extends TestCase
{
    // public function testEq()
    // {
    //     // $this->initDatabase('bookshop');

    //     // $row = (new Select($this->adapter('bookshop')))
    //     //     ->from('users')
    //     //     ->column('id')
    //     //     ->column('firstName')
    //     //     ->column('lastName')
    //     //     ->column('email')
    //     //     ->column('genderId')
    //     //     ->column('birthDate')
    //     //     ->column('ip')
    //     //     ->column('countryId')
    //     //     ->column('cityId')
    //     //     ->column('phone')
    //     //     ->eq('id', 1)
    //     //     ->fetch('row')
    //     // ;

    //     // // $this->createVariable('variable-38', $rows);
    //     // $this->assertEquals($this->variable('variable-16'), $row);
    // }

    public function testInto()
    {
        $insert = new Insert;

        $this->assertEquals($insert, $insert->into('users'));
        $this->assertEquals('users', $insert->into());
    }

    public function testTable()
    {
        $insert = new Insert;

        $this->assertEquals($insert, $insert->table('users'));
        $this->assertEquals('users', $insert->table());
    }

    public function testValues()
    {
        $insert = new Insert;

        $insert->values(array(
            array(
                'id' => 1,
                'name' => 'Pawel'
            ),
            array(
                'id' => 2,
                'name' => 'Pawel'
            ),
        ));

        $this->createVariable('variable-63', $insert->values());
        $this->assertEquals($this->variable('variable-63'), $insert->values());

        $this->assertEquals($insert, $insert->table('users'));
        $this->assertEquals('users', $insert->table());
    }

    // public function testValue()
    // {
    // }

    // public function testAdd()
    // {
    // }

    // public function testColumns()
    // {
    // }

    // public function testBuild()
    // {
    // }
}
