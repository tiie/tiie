<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Insert;
use Tiie\Data\Adapters\Commands\SQL\Expr;
use Tiie\Data\Adapters\Commands\SQL\Update;
use Tiie\Data\Adapters\Commands\Built;

class UpdateTest extends TestCase
{
    // public function testTable()
    // {
    //     $update = new Update();

    //     $this->assertEquals($update, $update->table('users'));
    //     $this->assertEquals('users', $update->table());
    // }

    public function testValues()
    {
        $update = new Update();

        $update->setValues(array(
            'id' => 1,
            'name' => 'Pawel',
        ));

        // $this->createVariable('variable-69', $update->getValues());
        $this->assertEquals($this->getVariable('variable-69'), $update->getValues());
    }

    public function testSet()
    {
        $update = new Update();

        $update->setValues(array(
            'id' => 1,
            'name' => 'Pawel',
        ));

        $this->assertEquals($update, $update->set('age', 10));

        // $this->createVariable('variable-70', $update->getValues());
        $this->assertEquals($this->getVariable('variable-70'), $update->getValues());
    }

    // todo Testowanie method Where w Update.
    // public function testBrackets()
    // public function testAndo()
    // public function testOro()
    // public function testIn()
    // public function testNotIn()
    // public function testIsNull()
    // public function testIsNotNull()
    // public function testStartWith()
    // public function testEndWith()
    // public function testContains()
    // public function testLike()
    // public function testConditions()
    // public function testEq()
    // public function testNeq()
    // public function testLt()
    // public function testLte()
    // public function testGt()
    // public function testGte()
    // public function testExpr()
    // public function testExists()
    // public function testNotExists()
    // public function testBetween()

    public function testBuilt()
    {
        $update = new Update();

        $update->setTable('users');
        $update->setValues(array(
            'id' => 1,
            'name' => 'Pawel',
        ));

        $this->assertEquals(true, $update->build() instanceof Built);
    }

    public function testUpdateRow()
    {
        $this->initDatabase('bookshop');

        $update = new Update($this->getAdapter('bookshop'));

        $update->setTable('users');
        $update->setValues(array(
            'lastName' => 'changed',
        ));

        $update->in('id', array(1, 2, 3));
        $update->execute();

        $rows = (new Select($this->getAdapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id',
                'firstName',
                'lastName',
                'email',
                'genderId',
                'birthDate',
                'ip',
                'countryId',
                'cityId',
                'phone',
            ))
            ->order('id asc')
            ->in('id', array(1, 2, 3))
            ->fetch()
            ->getData()
        ;

        $this->assertEquals(3, count($rows));

        $this->createVariable('variable-71', $rows);
        $this->assertEquals($this->getVariable('variable-71'), $rows);
    }
}
