<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Topi\Data\Adapters\Commands\SQL\Select;
use Topi\Data\Adapters\Commands\SQL\Expr;

class SelectTest extends TestCase
{
    public function testEq()
    {
        $this->initDatabase('bookshop');

        $row = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->column('firstName')
            ->column('lastName')
            ->column('email')
            ->column('genderId')
            ->column('birthDate')
            ->column('ip')
            ->column('countryId')
            ->column('cityId')
            ->column('phone')
            ->eq('id', 1)
            ->fetch('row')
        ;

        $this->assertEquals($this->variable('variable-16'), $row);
    }

    // public function test__clone()
    // {

    // }

    // public function testDefaultRule($defaultRule)
    // {

    // }

    // public function testRule($params, $rule = null)
    // {

    // }

    // public function testProcess($params = array())
    // {

    // }

    // public function testJoin($with, $on, $type = self::JOIN_LEFT)
    // {

    // }

    // public function testClean()
    // {

    // }

    // public function testLeftJoin($with, $on)
    // {

    // }

    // public function testRightJoin($with, $on)
    // {

    // }

    // public function testOuterJoin($with, $on)
    // {

    // }

    // public function testInnerJoin($with, $on)
    // {

    // }

    public function testLimit()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-17'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->limit(5, 5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-18'), $rows);

        // ---------------
        $this->expectException(\InvalidArgumentException::class);

        (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->limit(-1)
            ->fetch()
        ;

        (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->limit(-10, -11)
            ->fetch()
        ;
    }

    public function testPage()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page(0, 2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-19'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page(1, 2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-20'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page(array(
                'page' => 0,
                'pageSize' => 2,
            ))
            ->fetch()
        ;

        // $this->createVariable('variable-21', $rows);
        $this->assertEquals($this->variable('variable-19'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page(array(
                'page' => 1,
                'pageSize' => 2,
            ))
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-20'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page('1,2')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-20'), $rows);

        // ---------------
        $this->expectException(\InvalidArgumentException::class);

        (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page('1,2,3')
            ->fetch()
        ;

        // ---------------
        $this->expectException(\InvalidArgumentException::class);

        (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->page(-1, 0)
            ->fetch()
        ;
    }

    // public function testGroup($column)
    // {

    // }

    // public function testHaving($having)
    // {

    // }

    public function testOrder()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-21'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id desc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-22'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id', 'asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-21'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id', 'desc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-22'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id', 'DESC')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-22'), $rows);

    }

    public function testOrderInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order('id', 'DESCC')
            ->build()
        ;
    }

    public function testOrderExpr()
    {
        $this->initDatabase('bookshop');

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->order(new Expr("RAND()"))
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals(2, count($rows));
    }

    // public function testColumn($column, $alias = null)
    // {

    // }

    // public function testColumns(array $columns = null)
    // {

    // }

    // public function testFrom($table, $alias = null)
    // {

    // }

    // public function testBrackets($function)
    // {

    // }

    // public function testAndo()
    // {

    // }

    // public function testOro()
    // {

    // }

    // public function testIn($column, $value)
    // {

    // }

    // public function testNotIn($column, $value)
    // {

    // }

    // public function testIsNull($column)
    // {

    // }

    // public function testIsNotNull($column)
    // {

    // }

    // public function testStartWith($column, $value)
    // {

    // }

    // public function testEndWith($column, $value)
    // {

    // }

    // public function testContains($column, $value)
    // {

    // }

    // public function testLike($column, $value)
    // {

    // }

    // public function testConditions($column, $conditions, array $params = array())
    // {

    // }

    // public function testNeq($column, $value)
    // {

    // }

    // public function testLt($column, $value)
    // {

    // }

    // public function testLte($column, $value)
    // {

    // }

    // public function testGt($column, $value)
    // {

    // }

    // public function testGte($column, $value)
    // {

    // }

    // public function testExpr($expr)
    // {

    // }

    // public function testExists($value)
    // {

    // }

    // public function testNotExists($value)
    // {

    // }

    // public function testBetween($column, $begin, $end)
    // {

    // }

    // public function testBuild(array $params = array())
    // {

    // }

    // public function testCount($params = array())
    // {

    // }

    // public function testRandom()
    // {

    // }

    // public function testFetch($format = 'all', $params = array())
    // {

    // }

    // public function testParams()
    // {
    //     $select = (new Select())
    //         ->from('users')
    //     ;

    //     $select->params(array(
    //         'name-not-in' => array(1,2),
    //         'name-in' => array(4,5),
    //         'name-is-not-null' => 1,
    //         'name-is-null' => 1,
    //         'name-start-with' => 'start-with',
    //         'name-end-with' => 'end-with',
    //         'name-contains' => 'contains',
    //         'name-not-like' => 'not-like',
    //         'name-like' => 'like',
    //         'name-not-equal' => 'not-equal',
    //         'name-equal' => 'equal',
    //         'name-lower-than-equal' => 'lower-than-equal',
    //         'name-lower-than' => 'lower-than',
    //         'name-greater-than-equal' => 'greater-than-equal',
    //         'name-greater-than' => 'greater-than',
    //         'name-between' => array(1, 10)
    //     ), array(
    //         'name' => 'all',
    //     ));

    //     $this->assertEquals(1, 1);
    // }
}
