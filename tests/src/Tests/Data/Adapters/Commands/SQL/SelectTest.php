<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Expr;

// $this->createVariable('variable-38', $rows);
class SelectTest extends TestCase
{
    public function testEqual()
    {
        $this->initDatabase('bookshop');

        $select = new Select($this->getAdapter('bookshop'));

        $select->from('users');
        $select->column('id');
        $select->column('firstName');
        $select->column('lastName');
        $select->column('email');
        $select->column('genderId');
        $select->column('birthDate');
        $select->column('ip');
        $select->column('countryId');
        $select->column('cityId');
        $select->column('phone');

        $select->equal('id', 1);

        $row = $select->fetch()->format('row');

        $this->assertEquals($this->getVariable('variable-16'), $row);
    }

    public function testSort()
    {
        $this->initDatabase('bookshop');

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->setLimit(10);
        $select->sort("firstName asc");
        $select->columns(array(
            'id',
            'firstName',
            'lastName',
        ));

        $this->assertEquals($this->getVariable('variable-128'), $select->fetch()->getData());

        $select->sort("firstName desc");

        $this->assertEquals($this->getVariable('variable-129'), $select->fetch()->getData());
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

            // Simple filter
            $select = new Select($this->getAdapter('bookshop'));

            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setLimit(5);
            $select->$method($filterColumn, $filter);

            $base = $select->fetch()->getData();

            // Rows
            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->setParams(array(
                "{$filterColumnDashed}-{$value['dashed']}" => $filter,
            ));

            $select->order('id asc');
            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumn}{$value['camel']}" => $filter,
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            // with defined fields
            // -----------------------------------
            $select = new Select($this->getAdapter('bookshop'));

            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->$method($filterColumn, $filter);
            $select->setLimit(5);

            $base = $select->fetch()->getData();

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');

            $select->setParams(array(
                "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                "id-equal" => 20,
            ), array(
                $filterColumn,
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumn}{$value['camel']}" => $filter,
                "idEqual" => 20,
            ), array(
                $filterColumn,
            ));

            $select->setLimit(5);

            $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            // with defined operations
            // -----------------------------------
            $select = new Select($this->getAdapter('bookshop'));

            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));
            $select->order('id asc');
            $select->$method($filterColumn, $filter);
            $select->setLimit(5);

            $base = $select->fetch()->getData();

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                "{$filterColumnDashed}-{$value['dashedNegated']}" => $filter,
                "id-equal" => 20,
            ), array(
                $filterColumn => array(
                    'operations' => array($value['operation']),
                )
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumn}{$value['camel']}" => $filter,
                "{$filterColumn}{$value['camelNegated']}" => $filter,
                "idEqual" => 20,
            ), array(
                $filterColumn => array(
                    'operations' => array($value['operation']),
                )
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            // with defined operations
            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));
            $select->order('id asc');
            $select->$methodNegated($filterColumn, $filter);
            $select->setLimit(5);

            $base = $select->fetch()->getData();

            $select = new Select($this->getAdapter('bookshop'));

            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumnDashed}-{$value['dashed']}" => $filter,
                "{$filterColumnDashed}-{$value['dashedNegated']}" => $filter,
                "id-equal" => 20,
            ), array(
                $filterColumn => array(
                    'excluded' => array($value['operation']),
                )
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);

            $select = new Select($this->getAdapter('bookshop'));
            $select->from('users');
            $select->columns(array(
                'id',
                'countryId',
                'firstName',
            ));

            $select->order('id asc');
            $select->setParams(array(
                "{$filterColumn}{$value['camel']}" => $filter,
                "{$filterColumn}{$value['camelNegated']}" => $filter,
                "idEqual" => 20,
            ), array(
                $filterColumn => array(
                    'excluded' => array($value['operation']),
                )
            ));

            $select->setLimit(5);

            $rows = $select->fetch()->getData();

            $this->assertEquals($base, $rows);
        }
    }

    public function testLimit()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-17'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setLimit(5, 5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-18'), $rows);
    }

    public function testPage()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage(0, 2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-19'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage(1, 2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-20'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage(array(
            'page' => 0,
            'pageSize' => 2,
        ));

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-19'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage(array(
            'page' => 1,
            'pageSize' => 2,
        ));

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-20'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage('1,2');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-20'), $rows);

        // ---------------
        $this->expectException(\InvalidArgumentException::class);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage('1,2,3');

        $select->fetch()->getData();

        // ---------------
        $this->expectException(\InvalidArgumentException::class);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setPage(-1, 0);

        $select->fetch()->getData();
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
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-21'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id desc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-22'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-21'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id desc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-22'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order('id desc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-22'), $rows);

    }

    public function testOrderExpr()
    {
        $this->initDatabase('bookshop');

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->order(new Expr("RAND()"));
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals(2, count($rows));
    }

    public function testColumn()
    {
        $this->initDatabase('bookshop');

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('firstName');
        $select->column('lastName');
        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-23'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-24'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->column(new Expr("concat(id, '-', firstName, '-', lastName)"), 'fullName');
        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-25'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('firstName', 'first');
        $select->column('lastName', 'last');
        $select->column(new Expr("concat(id, '-', firstName, '-', lastName)"), 'fullName');
        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-26'), $rows);
    }

    public function testColumns()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->columns(array(
            'id',
            'firstName',
            'fullName' => new Expr("concat(id, '-', firstName, '-', lastName)")
        ));

        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-27'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->columns(array(
                'id',
                'firstName',
            ));
        ;

        $this->assertEquals($this->getVariable('variable-28'), $select->columns());
    }

    public function testFrom()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->setLimit(2);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-29'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users');
        $select->column('id');
        $select->column('firstName');
        $select->column('lastName');
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-31'), $rows);

        // ---------------
        $sub = new Select();
        $sub->from('users', 'sub');
        $sub->column('id');
        $sub->column('email');
        $sub->setLimit(10);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from($sub, 'base');
        $select->order('base.id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-30'), $rows);
    }

    public function testIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->in('u.id', array(11, 12, 13, 14, 15));
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-32'), $rows);

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->in('u.id', array());
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-33'), $rows);
    }

    public function testInSubSelect()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $sub = new Select();
        $sub->from('users');
        $sub->column('id');
        $sub->in('u.id', array(11, 12, 13, 14, 15, 'test'));

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->in('u.id', $sub);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-34'), $rows);
    }

    public function testNotIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->notIn('u.id', array(1, 2, 3, 4, 5));
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-35'), $rows);
    }

    public function testNotInSubSelect()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $sub = new Select();
        $sub->from('users');
        $sub->column('id');
        $sub->in('u.id', array(1, 2, 3, 4, 5, 'test'));

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->notIn('u.id', $sub);
        $select->setLimit(5);
        $select->order('id asc');

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-36'), $rows);
    }

    public function testIsNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->column('u.countryId');
        $select->isNull('u.countryId');
        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-37'), $rows);
    }

    public function testIsNotNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.countryId');
        $select->isNotNull('u.countryId');
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-38'), $rows);
    }

    public function testStartWith()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        // $rows = new Select($this->getAdapter('bookshop'));
        //     ->from('users', 'u')
        //     ->column('u.id')
        //     ->column('u.countryId')
        //     ->column('u.firstName')
        //     ->column('u.lastName')
        //     ->isNotNull('u.countryId')
        //     ->startWith('u.firstName', 'Ali')
        //     ->order('id asc')
        //     ->setLimit(2)
        //     ->fetch()
        //     ->getData()
        // ;

        // $this->assertEquals($this->getVariable('variable-39'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.countryId');
        $select->column('u.firstName');
        $select->column('u.lastName');
        $select->isNotNull('u.countryId');
        $select->startWith('u.firstName', "Aliz");
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-40'), $rows);
    }

    public function testEndWith()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->endWith('u.firstName', 'trée');
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-41'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->endWith('u.firstName', "trée");
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-42'), $rows);

    }

    public function testContains()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->contains('u.firstName', 'ébec');
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-43'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->contains('u.firstName', "ébec");
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-44'), $rows);
    }

    public function testLike()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->contains('u.firstName', 'No%l%a');
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-45'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->contains('u.firstName', new Expr('"No%l%a"'));
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-46'), $rows);
    }

    public function testConditions()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->conditions('u.id', 1);
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-47'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->conditions('u.id', array(1, 2, 3, 4));
        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-48'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->conditions('u.id', array(
            'from' => 1,
            'to' => 4
        ));

        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-49'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->conditions('u.firstName', array(
                'réé',
                'áo',
                'éli',
            ), array(
                'operator' => 'contains'
            ));
        $select->order('id asc');
        $select->setLimit(10);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-50'), $rows);
    }

    public function testNotEqual()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->notEqual('u.id', 1);
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-51'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->notEqual('u.id', new Expr('1'));
        $select->order('id asc');
        $select->setLimit(2);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-52'), $rows);
    }

    public function testLowerThan()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->lowerThan('u.id', 5);
        $select->order('id asc');
        $select->setLimit(4);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-53'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->lowerThan('u.id', new Expr('5'));
        $select->order('id asc');
        $select->setLimit(4);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-54'), $rows);
    }

    public function testLowerThanEqual()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->lowerThanEqual('u.id', 5);
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-55'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->lowerThanEqual('u.id', new Expr('5'));
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-56'), $rows);
    }

    public function testGreaterThan()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->greaterThan('u.id', 5);
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-57'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->greaterThan('u.id', new Expr('5'));
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-58'), $rows);
    }

    public function testGreaterThanEqual()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->greaterThanEqual('u.id', 5);
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-59'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->greaterThanEqual('u.id', new Expr('5'));
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch()->getData();

        $this->assertEquals($this->getVariable('variable-60'), $rows);
    }

    public function testExpr()
    {
        $this->initDatabase('bookshop');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->expr('u.id >= :from and u.id <= :to');
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch(array(
            'from' => 10,
            'to' => '20',
        ))->getData();

        $this->assertEquals($this->getVariable('variable-61'), $rows);

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->column('u.id');
        $select->column('u.firstName');
        $select->expr(new Expr('u.id >= :from and u.id <= :to'));
        $select->order('id asc');
        $select->setLimit(5);

        $rows = $select->fetch(array(
            'from' => 10,
            'to' => '20',
        ))->getData();

        $this->assertEquals($this->getVariable('variable-62'), $rows);
    }

    public function testExists()
    {
        $this->initDatabase('bookshop');

        $exists = new Select();
        $exists->from('users', 'u2');
        $exists->expr('u2.firstName = u.firstName');
        $exists->group('u2.firstName');
        $exists->having('count(u2.firstName) = 2');

        // // ---------------
        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->columns(array(
            'u.id',
            'u.firstName',
        ));
        $select->order('id asc');
        $select->exists($exists);

        $rows = $select->fetch()->getData();

        $this->assertEquals(158, count($rows));
    }

    public function testNotExists()
    {
        $this->initDatabase('bookshop');

        $exists = new Select();
        $exists->from('users', 'u2');
        $exists->expr('u2.firstName = u.firstName');
        $exists->group('u2.firstName');
        $exists->having('count(u2.firstName) = 2');

        $select = new Select($this->getAdapter('bookshop'));
        $select->from('users', 'u');
        $select->columns(array(
            'u.id',
            'u.firstName',
        ));

        $select->order('id asc');
        $select->notExists($exists);

        $rows = $select->fetch()->getData();

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
    //     $select = new Select();
    //         ->from('users')
    //     ;

    //     $select->setParams(array(
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
