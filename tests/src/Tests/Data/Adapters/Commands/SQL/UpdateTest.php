<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Elusim\Data\Adapters\Commands\SQL\Select;
use Elusim\Data\Adapters\Commands\SQL\Insert;
use Elusim\Data\Adapters\Commands\SQL\Expr;
use Elusim\Data\Adapters\Commands\SQL\Update;
use Elusim\Data\Adapters\Commands\BuiltCommand;

class UpdateTest extends TestCase
{
    public function testTable()
    {
        $update = new Update();

        $this->assertEquals($update, $update->table('users'));
        $this->assertEquals('users', $update->table());
    }

    public function testValues()
    {
        $update = new Update();

        $this->assertEquals($update, $update->values(array(
            'id' => 1,
            'name' => 'Pawel',
        )));

        // $this->createVariable('variable-69', $update->values());
        $this->assertEquals($this->variable('variable-69'), $update->values());
    }

    public function testSet()
    {
        $update = new Update();

        $this->assertEquals($update, $update->values(array(
            'id' => 1,
            'name' => 'Pawel',
        )));

        $this->assertEquals($update, $update->set('age', 10));

        // $this->createVariable('variable-70', $update->values());
        $this->assertEquals($this->variable('variable-70'), $update->values());
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

    public function testBuild()
    {
        $update = new Update();

        $update->table('users')
            ->values(array(
                'id' => 1,
                'name' => 'Pawel',
            ))
        ;

        $this->assertEquals(true, $update->build() instanceof BuiltCommand);
    }

    public function testUpdateRow()
    {
        $this->initDatabase('bookshop');

        $update = new Update($this->adapter('bookshop'));

        $update->table('users')
            ->values(array(
                'lastName' => 'changed',
            ))
            ->in('id', array(1, 2, 3))
            ->execute()
        ;

        $rows = (new Select($this->adapter('bookshop')))
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
            ->data()
        ;

        $this->assertEquals(3, count($rows));

        $this->createVariable('variable-71', $rows);
        $this->assertEquals($this->variable('variable-71'), $rows);
    }
}
