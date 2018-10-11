<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Topi\Data\Adapters\Commands\SQL\Select;
use Topi\Data\Adapters\Commands\SQL\Expr;

// $this->createVariable('variable-38', $rows);
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

    public function testParams()
    {
        $this->initDatabase('bookshop');

        $map = array(
            array(
                'operation' => 'in',
                'method' => 'in',
                'methodNegated' => 'notIn',
                'dashed' => 'in',
                'dashedNegated' => 'not-in',
                'camel' => 'In',
                'camelNegated' => 'NotIn',
            ),
            array(
                'operation' => 'notIn',
                'method' => 'notIn',
                'methodNegated' => 'in',
                'dashed' => 'not-in',
                'dashedNegated' => 'in',
                'camel' => 'NotIn',
                'camelNegated' => 'In',
            ),
            array(
                'operation' => 'isNull',
                'method' => 'isNull',
                'methodNegated' => 'isNotNull',
                'dashed' => 'is-null',
                'dashedNegated' => 'is-not-null',
                'camel' => 'IsNull',
                'camelNegated' => 'IsNotNull',
            ),
            array(
                'operation' => 'isNotNull',
                'method' => 'isNotNull',
                'methodNegated' => 'isNull',
                'dashed' => 'is-not-null',
                'dashedNegated' => 'is-null',
                'camel' => 'IsNotNull',
                'camelNegated' => 'IsNull',
            ),
            array(
                'operation' => 'startWith',
                'method' => 'startWith',
                'methodNegated' => 'notStartWith',
                'dashed' => 'start-with',
                'dashedNegated' => 'not-start-with',
                'camel' => 'StartWith',
                'camelNegated' => 'NotStartWith',
            ),
            array(
                'operation' => 'notStartWith',
                'method' => 'notStartWith',
                'methodNegated' => 'startWith',
                'dashed' => 'not-start-with',
                'dashedNegated' => 'start-with',
                'camel' => 'NotStartWith',
                'camelNegated' => 'StartWith',
            ),
            array(
                'operation' => 'endWith',
                'method' => 'endWith',
                'methodNegated' => 'notEndWith',
                'dashed' => 'end-with',
                'dashedNegated' => 'not-end-with',
                'camel' => 'EndWith',
                'camelNegated' => 'NotEndWith',
            ),
            array(
                'operation' => 'notEndWith',
                'method' => 'notEndWith',
                'methodNegated' => 'endWith',
                'dashed' => 'not-end-with',
                'dashedNegated' => 'end-with',
                'camel' => 'NotEndWith',
                'camelNegated' => 'EndWith',
            ),
            array(
                'operation' => 'contains',
                'method' => 'contains',
                'methodNegated' => 'notContains',
                'dashed' => 'contains',
                'dashedNegated' => 'not-contains',
                'camel' => 'Contains',
                'camelNegated' => 'NotContains',
            ),
            array(
                'operation' => 'notContains',
                'method' => 'notContains',
                'methodNegated' => 'contains',
                'dashed' => 'not-contains',
                'dashedNegated' => 'contains',
                'camel' => 'NotContains',
                'camelNegated' => 'Contains',
            ),
            array(
                'operation' => 'like',
                'method' => 'like',
                'methodNegated' => 'notLike',
                'dashed' => 'like',
                'dashedNegated' => 'not-like',
                'camel' => 'Like',
                'camelNegated' => 'NotLike',
            ),
            array(
                'operation' => 'notLike',
                'method' => 'notLike',
                'methodNegated' => 'like',
                'dashed' => 'not-like',
                'dashedNegated' => 'like',
                'camel' => 'NotLike',
                'camelNegated' => 'Like',
            ),
            array(
                'operation' => 'equal',
                'method' => 'equal',
                'methodNegated' => 'notEqual',
                'dashed' => 'equal',
                'dashedNegated' => 'not-equal',
                'camel' => 'Equal',
                'camelNegated' => 'NotEqual',
            ),
            array(
                'operation' => 'notEqual',
                'method' => 'notEqual',
                'methodNegated' => 'equal',
                'dashed' => 'not-equal',
                'dashedNegated' => 'equal',
                'camel' => 'NotEqual',
                'camelNegated' => 'Equal',
            ),
            array(
                'operation' => 'lowerThanEqual',
                'method' => 'lowerThanEqual',
                'methodNegated' => 'notLowerThanEqual',
                'dashed' => 'lower-than-equal',
                'dashedNegated' => 'not-lower-than-equal',
                'camel' => 'LowerThanEqual',
                'camelNegated' => 'NotLowerThanEqual',
            ),
            array(
                'operation' => 'notLowerThanEqual',
                'method' => 'notLowerThanEqual',
                'methodNegated' => 'lowerThanEqual',
                'dashed' => 'not-lower-than-equal',
                'dashedNegated' => 'lower-than-equal',
                'camel' => 'NotLowerThanEqual',
                'camelNegated' => 'LowerThanEqual',
            ),
            array(
                'operation' => 'lowerThan',
                'method' => 'lowerThan',
                'methodNegated' => 'notLowerThan',
                'dashed' => 'lower-than',
                'dashedNegated' => 'not-lower-than',
                'camel' => 'LowerThan',
                'camelNegated' => 'NotLowerThan',
            ),
            array(
                'operation' => 'notLowerThan',
                'method' => 'notLowerThan',
                'methodNegated' => 'lowerThan',
                'dashed' => 'not-lower-than',
                'dashedNegated' => 'lower-than',
                'camel' => 'NotLowerThan',
                'camelNegated' => 'LowerThan',
            ),
            array(
                'operation' => 'greaterThanEqual',
                'method' => 'greaterThanEqual',
                'methodNegated' => 'notGreaterThanEqual',
                'dashed' => 'greater-than-equal',
                'dashedNegated' => 'not-greater-than-equal',
                'camel' => 'GreaterThanEqual',
                'camelNegated' => 'NotGreaterThanEqual',
            ),
            array(
                'operation' => 'notGreaterThanEqual',
                'method' => 'notGreaterThanEqual',
                'methodNegated' => 'greaterThanEqual',
                'dashed' => 'not-greater-than-equal',
                'dashedNegated' => 'greater-than-equal',
                'camel' => 'NotGreaterThanEqual',
                'camelNegated' => 'GreaterThanEqual',
            ),
            array(
                'operation' => 'greaterThan',
                'method' => 'greaterThan',
                'methodNegated' => 'notGreaterThan',
                'dashed' => 'greater-than',
                'dashedNegated' => 'not-greater-than',
                'camel' => 'GreaterThan',
                'camelNegated' => 'NotGreaterThan',
            ),
            array(
                'operation' => 'notGreaterThan',
                'method' => 'notGreaterThan',
                'methodNegated' => 'greaterThan',
                'dashed' => 'not-greater-than',
                'dashedNegated' => 'greater-than',
                'camel' => 'NotGreaterThan',
                'camelNegated' => 'GreaterThan',
            ),
            array(
                'operation' => 'between',
                'method' => 'between',
                'methodNegated' => 'notBetween',
                'dashed' => 'between',
                'dashedNegated' => 'not-between',
                'camel' => 'Between',
                'camelNegated' => 'NotBetween',
            ),
            array(
                'operation' => 'notBetween',
                'method' => 'notBetween',
                'methodNegated' => 'between',
                'dashed' => 'not-between',
                'dashedNegated' => 'between',
                'camel' => 'NotBetween',
                'camelNegated' => 'Between',
            ),
        );

        foreach ($map as $key => $value) {
            $method = $value['method'] ;
            $methodNegated = $value['methodNegated'] ;
            $filter = null;
            $filterColumn = null;
            $filterColumnDashed = null;

            if (in_array($value['operation'], array(
                'isNull',
                'isNotNull',
                'equal',
                'notEqual',
                'lowerThanEqual',
                'notLowerThanEqual',
                'lowerThan',
                'notLowerThan',
                'greaterThanEqual',
                'notGreaterThanEqual',
                'greaterThan',
                'notGreaterThan',
            ))) {
                $filterColumn = 'countryId';
                $filterColumnDashed = 'country-id';
                $filter = 126;

            } elseif (in_array($value['operation'], array(
                'startWith',
                'notStartWith',
                'endWith',
                'notEndWith',
                'contains',
                'notContains',
                'like',
                'notLike',
            ))) {
                $filterColumn = 'firstName';
                $filterColumnDashed = 'first-name';
                $filter = 'A';
            } elseif (in_array($value['operation'], array(
                // 'between',
                // 'notBetween',
            ))) {
                $filterColumn = 'countryId';
                $filterColumnDashed = 'country-id';
                $filter = '2:3';

            } elseif (in_array($value['operation'], array(
                'in',
                'notIn',
            ))) {
                $filterColumn = 'countryId';
                $filterColumnDashed = 'country-id';
                $filter = array(126);
            } else {
                continue;
            }

            // simple filter
            $base = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->$method($filterColumn, $filter)
                ->limit(5)
                ->fetch()
            ;

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumn}{$value['camel']}" => $filter,
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            // with defined fields
            $base = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->$method($filterColumn, $filter)
                ->limit(5)
                ->fetch()
            ;

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                    "id-equal" => 20,
                ), array(
                    $filterColumn,
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumn}{$value['camel']}" => $filter,
                    "idEqual" => 20,
                ), array(
                    $filterColumn,
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            // with defined operations
            $base = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->$method($filterColumn, $filter)
                ->limit(5)
                ->fetch()
            ;

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                    "{$filterColumnDashed}-{$value['dashedNegated']}" => $filter,
                    "id-equal" => 20,
                ), array(
                    $filterColumn => array(
                        'operations' => array($value['operation']),
                    )
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumn}{$value['camel']}" => $filter,
                    "{$filterColumn}{$value['camelNegated']}" => $filter,
                    "idEqual" => 20,
                ), array(
                    $filterColumn => array(
                        'operations' => array($value['operation']),
                    )
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            // with defined operations
            $base = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->$methodNegated($filterColumn, $filter)
                ->limit(5)
                ->fetch()
            ;

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                    "{$filterColumnDashed}-{$value['dashedNegated']}" => $filter,
                    "id-equal" => 20,
                ), array(
                    $filterColumn => array(
                        'excluded' => array($value['operation']),
                    )
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);

            $rows = (new Select($this->adapter('bookshop')))
                ->from('users')
                ->columns(array(
                    'id',
                    'countryId',
                    'firstName',
                ))
                ->order('id asc')
                ->params(array(
                    "{$filterColumn}{$value['camel']}" => $filter,
                    "{$filterColumn}{$value['camelNegated']}" => $filter,
                    "idEqual" => 20,
                ), array(
                    $filterColumn => array(
                        'excluded' => array($value['operation']),
                    )
                ))
                ->limit(5)
                ->fetch()
            ;

            $this->assertEquals($base, $rows);
        }
    }

    // public function testParamsBetween()
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

    public function testColumn()
    {
        $this->initDatabase('bookshop');

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('firstName')
            ->column('lastName')
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-23'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-24'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->column(new Expr("concat(id, '-', firstName, '-', lastName)"), 'fullName')
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-25'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('firstName', 'first')
            ->column('lastName', 'last')
            ->column(new Expr("concat(id, '-', firstName, '-', lastName)"), 'fullName')
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-26'), $rows);
    }

    public function testColumns()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->columns(array(
                'id',
                'firstName',
                'fullName' => new Expr("concat(id, '-', firstName, '-', lastName)")
            ))
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-27'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->columns(array(
                'id',
                'firstName',
            ))
        ;

        $this->assertEquals($this->variable('variable-28'), $rows->columns());
    }

    public function testFrom()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->limit(2)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-29'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users')
            ->column('id')
            ->column('firstName')
            ->column('lastName')
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-31'), $rows);

        // ---------------
        $sub = (new Select())
            ->from('users', 'sub')
            ->column('id')
            ->column('email')
            ->limit(10)
        ;

        $rows = (new Select($this->adapter('bookshop')))
            ->from($sub, 'base')
            ->order('base.id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-30'), $rows);
    }

    public function testFromInvalidAlias()
    {
        $this->expectException(\InvalidArgumentException::class);

        $sub = (new Select())
            ->from('users', 'sub')
            ->column('id')
            ->column('email')
            ->limit(10)
        ;

        $rows = (new Select($this->adapter('bookshop')))
            ->from($sub)
            ->order('base.id asc')
            ->fetch()
        ;
    }

    public function testIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->in('u.id', array(11, 12, 13, 14, 15))
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-32'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->in('u.id', array())
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-33'), $rows);
    }

    public function testInSubSelect()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $sub = (new Select())
            ->from('users')
            ->column('id')
            ->in('u.id', array(11, 12, 13, 14, 15, 'test'))
        ;

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->in('u.id', $sub)
            ->order('id asc')
            // ->build()
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-34'), $rows);
    }

    public function testNotIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->notIn('u.id', array(1, 2, 3, 4, 5))
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-35'), $rows);
    }

    public function testNotInSubSelect()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $sub = (new Select())
            ->from('users')
            ->column('id')
            ->in('u.id', array(1, 2, 3, 4, 5, 'test'))
        ;

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->notIn('u.id', $sub)
            ->limit(5)
            ->order('id asc')
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-36'), $rows);
    }

    public function testIsNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->column('u.lastName')
            ->column('u.countryId')
            ->isNull('u.countryId')
            ->order('id asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-37'), $rows);
    }

    public function testIsNotNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.countryId')
            ->isNotNull('u.countryId')
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-38'), $rows);
    }

    public function testStartWith()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.countryId')
            ->column('u.firstName')
            ->column('u.lastName')
            ->isNotNull('u.countryId')
            ->startWith('u.firstName', 'Ali')
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-39'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.countryId')
            ->column('u.firstName')
            ->column('u.lastName')
            ->isNotNull('u.countryId')
            ->startWith('u.firstName', new Expr('"Aliz"'))
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-40'), $rows);
    }

    public function testEndWith()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->endWith('u.firstName', 'trée')
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-41'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->endWith('u.firstName', new Expr('"trée"'))
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-42'), $rows);

    }

    public function testContains()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->contains('u.firstName', 'ébec')
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-43'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->contains('u.firstName', new Expr('"ébec"'))
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-44'), $rows);
    }

    public function testLike()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->contains('u.firstName', 'No%l%a')
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-45'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->contains('u.firstName', new Expr('"No%l%a"'))
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-46'), $rows);
    }

    public function testConditions()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->conditions('u.id', 1)
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-47'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->conditions('u.id', array(1, 2, 3, 4))
            ->order('id asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-48'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->conditions('u.id', array(
                'from' => 1,
                'to' => 4
            ))
            ->order('id asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-49'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->conditions('u.firstName', array(
                'réé',
                'áo',
                'éli',
            ), array(
                'operator' => 'contains'
            ))
            ->order('id asc')
            ->limit(10)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-50'), $rows);
    }

    public function testNeq()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->neq('u.id', 1)
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-51'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->neq('u.id', new Expr('1'))
            ->order('id asc')
            ->limit(2)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-52'), $rows);
    }

    public function testLt()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->lt('u.id', 5)
            ->order('id asc')
            ->limit(4)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-53'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->lt('u.id', new Expr('5'))
            ->order('id asc')
            ->limit(4)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-54'), $rows);
    }

    public function testLte()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->lte('u.id', 5)
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-55'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->lte('u.id', new Expr('5'))
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-56'), $rows);
    }

    public function testGt()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->gt('u.id', 5)
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-57'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->gt('u.id', new Expr('5'))
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-58'), $rows);
    }

    public function testGte()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->gte('u.id', 5)
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-59'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->gte('u.id', new Expr('5'))
            ->order('id asc')
            ->limit(5)
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-60'), $rows);
    }

    public function testExpr()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->expr('u.id >= :from and u.id <= :to')
            ->order('id asc')
            ->limit(5)
            ->fetch('all', array(
                'from' => 10,
                'to' => '20',
            ))
        ;

        $this->assertEquals($this->variable('variable-61'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->expr(new Expr('u.id >= :from and u.id <= :to'))
            ->order('id asc')
            ->limit(5)
            ->fetch('all', array(
                'from' => 10,
                'to' => '20',
            ))
        ;

        $this->assertEquals($this->variable('variable-62'), $rows);
    }

    public function testExists()
    {
        $this->initDatabase('bookshop');

        $exists = (new Select())
            ->from('users', 'u2')
            ->expr('u2.firstName = u.firstName')
            ->group('u2.firstName')
            ->having('count(u2.firstName) = 2')
        ;

        // // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'u.id',
                'u.firstName',
            ))
            ->order('id asc')
            // ->limit(5)
            ->exists($exists)
            ->fetch('all')
        ;

        $this->assertEquals(158, count($rows));
    }

    public function testNotExists()
    {
        $this->initDatabase('bookshop');

        $exists = (new Select())
            ->from('users', 'u2')
            ->expr('u2.firstName = u.firstName')
            ->group('u2.firstName')
            ->having('count(u2.firstName) = 2')
        ;

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'u.id',
                'u.firstName',
            ))
            ->order('id asc')
            // ->limit(5)
            ->notExists($exists)
            ->fetch('all')
        ;

        $this->assertEquals(2000 - 158, count($rows));
    }

    // public function testBetween()
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

    // public function testBrackets($function)
    // {

    // }

    // public function testAndo()
    // {

    // }

    // public function testOro()
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
