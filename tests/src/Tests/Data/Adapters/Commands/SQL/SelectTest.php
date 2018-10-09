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

    public function testParamsNotIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idNotIn' => array(1, 2),
                'nameNotIn' => array('André'),
            ), array(
                'id' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-73', $rows);
        $this->assertEquals($this->variable('variable-73'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idNotIn' => array(1, 2),
                'nameNotIn' => array('André'),
            ), array(
                'id' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-74', $rows);
        $this->assertEquals($this->variable('variable-74'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idNotIn' => array(1, 2),
                'idEqual' => 10,
                'nameNotIn' => array('André'),
            ), array(
                'id' => array(
                    'operations' => array('notIn')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-75', $rows);
        $this->assertEquals($this->variable('variable-75'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idNotIn' => array(1, 2),
                'idEqual' => 10,
                'nameNotIn' => array('André'),
            ), array(
                'id' => array(
                    'excluded' => array('notIn')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-76', $rows);
        $this->assertEquals($this->variable('variable-76'), $rows);


        // dashed params
        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-not-in' => array(1, 2),
                'name-not-in' => array('André'),
            ), array(
                'id' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-73', $rows);
        $this->assertEquals($this->variable('variable-73'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-not-in' => array(1, 2),
                'name-not-in' => array('André'),
            ), array(
                'id' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-74', $rows);
        $this->assertEquals($this->variable('variable-74'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-not-in' => array(1, 2),
                'id-equal' => 10,
                'name-not-in' => array('André'),
            ), array(
                'id' => array(
                    'operations' => array('notIn')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-75', $rows);
        $this->assertEquals($this->variable('variable-75'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-not-in' => array(1, 2),
                'id-equal' => 10,
                'name-not-in' => array('André'),
            ), array(
                'id' => array(
                    'excluded' => array('notIn')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-76', $rows);
        $this->assertEquals($this->variable('variable-76'), $rows);
    }

    public function testParamsIn()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idIn' => array(1, 2),
                'nameIn' => array('André'),
            ), array(
                'id' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-77', $rows);
        $this->assertEquals($this->variable('variable-77'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idIn' => array(1, 2),
                'nameIn' => array('André'),
            ), array(
                'id' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-78', $rows);
        $this->assertEquals($this->variable('variable-78'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idIn' => array(1, 2),
                'idEqual' => 10,
                'nameIn' => array('André'),
            ), array(
                'id' => array(
                    'operations' => array('in')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-79', $rows);
        $this->assertEquals($this->variable('variable-79'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'idIn' => array(1, 2),
                'idEqual' => 10,
                'nameIn' => array('André'),
            ), array(
                'id' => array(
                    'excluded' => array('in')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-80', $rows);
        $this->assertEquals($this->variable('variable-80'), $rows);

        // dashed params
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-in' => array(1, 2),
                'name-in' => array('André'),
            ), array(
                'id' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-77', $rows);
        $this->assertEquals($this->variable('variable-77'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-in' => array(1, 2),
                'name-in' => array('André'),
            ), array(
                'id' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-78', $rows);
        $this->assertEquals($this->variable('variable-78'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-in' => array(1, 2),
                'id-equal' => 10,
                'name-in' => array('André'),
            ), array(
                'id' => array(
                    'operations' => array('in')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-79', $rows);
        $this->assertEquals($this->variable('variable-79'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'id-in' => array(1, 2),
                'id-equal' => 10,
                'name-in' => array('André'),
            ), array(
                'id' => array(
                    'excluded' => array('in')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-80', $rows);
        $this->assertEquals($this->variable('variable-80'), $rows);

    }

    public function testParamsIsNotNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'countryIdIsNotNull' => 1,
                'nameIsNull' => 1,
            ), array(
                'countryId' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-81', $rows);
        $this->assertEquals($this->variable('variable-81'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'countryIdIsNotNull' => 1,
                'nameIsNull' => 1,
            ), array(
                'countryId' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-82', $rows);
        $this->assertEquals($this->variable('variable-82'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'countryIdIsNotNull' => 1,
                'countryIdIsNull' => 1,
                'idEqual' => 10,
            ), array(
                'countryId' => array(
                    'operations' => array('isNotNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-83', $rows);
        $this->assertEquals($this->variable('variable-83'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'countryIdIsNotNull' => 1,
                'countryIdIsNull' => 1,
                'idEqual' => 10,
            ), array(
                'countryId' => array(
                    'excluded' => array('isNotNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-84', $rows);
        $this->assertEquals($this->variable('variable-84'), $rows);

        // dashed params
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'country-id-is-not-null' => 1,
                'nameIsNull' => 1,
            ), array(
                'countryId' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-81', $rows);
        $this->assertEquals($this->variable('variable-81'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'country-id-is-not-null' => 1,
                'name-is-null' => 1,
            ), array(
                'countryId' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-82', $rows);
        $this->assertEquals($this->variable('variable-82'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'country-id-is-not-null' => 1,
                'country-id-is-null' => 1,
                'id-equal' => 10,
            ), array(
                'countryId' => array(
                    'operations' => array('isNotNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-83', $rows);
        $this->assertEquals($this->variable('variable-83'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'country-id-is-not-null' => 1,
                'country-id-is-null' => 1,
                'id-equal' => 10,
            ), array(
                'countryId' => array(
                    'excluded' => array('isNotNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-84', $rows);
        $this->assertEquals($this->variable('variable-84'), $rows);
    }

    public function testParamsIsNull()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'countryIdIsNull' => 1,
                'nameIsNull' => 1,
            ), array(
                'countryId' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-85', $rows);
        $this->assertEquals($this->variable('variable-85'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'countryIdIsNull' => 1,
                'nameIsNull' => 1,
            ), array(
                'countryId' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-86', $rows);
        $this->assertEquals($this->variable('variable-86'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'countryIdIsNull' => 1,
                'countryIdIsNotNull' => 1,
                'idEqual' => 10,
            ), array(
                'countryId' => array(
                    'operations' => array('isNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-87', $rows);
        $this->assertEquals($this->variable('variable-87'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'countryIdIsNull' => 1,
                'countryIdIsNotNull' => 1,
                'idEqual' => 10,
            ), array(
                'countryId' => array(
                    'excluded' => array('isNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-88', $rows);
        $this->assertEquals($this->variable('variable-88'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'country-id-is-null' => 1,
                'name-is-null' => 1,
            ), array(
                'countryId' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-85', $rows);
        $this->assertEquals($this->variable('variable-85'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'country-id-is-null' => 1,
                'name-is-null' => 1,
            ), array(
                'countryId' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-86', $rows);
        $this->assertEquals($this->variable('variable-86'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'country-id-is-null' => 1,
                'country-id-is-not-null' => 1,
                'id-equal' => 10,
            ), array(
                'countryId' => array(
                    'operations' => array('isNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-87', $rows);
        $this->assertEquals($this->variable('variable-87'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
            ))
            ->params(array(
                'country-id-is-null' => 1,
                'country-id-is-not-null' => 1,
                'idEqual' => 10,
            ), array(
                'countryId' => array(
                    'excluded' => array('isNull')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-88', $rows);
        $this->assertEquals($this->variable('variable-88'), $rows);
    }

    public function testParamsStartWith()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-89', $rows);
        $this->assertEquals($this->variable('variable-89'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-90', $rows);
        $this->assertEquals($this->variable('variable-90'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameStartWith' => 'Pub',
                'firstNameEndWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('startWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-91', $rows);
        $this->assertEquals($this->variable('variable-91'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameStartWith' => 'Pub',
                'firstNameEndWith' => 'née',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('startWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-92', $rows);
        $this->assertEquals($this->variable('variable-92'), $rows);

        // dashed params
        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-89', $rows);
        $this->assertEquals($this->variable('variable-89'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-90', $rows);
        $this->assertEquals($this->variable('variable-90'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-start-with' => 'Pub',
                'first-name-end-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('startWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-91', $rows);
        $this->assertEquals($this->variable('variable-91'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-start-with' => 'Pub',
                'first-name-end-with' => 'née',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('startWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-92', $rows);
        $this->assertEquals($this->variable('variable-92'), $rows);
    }

    public function testParamsEndWith()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameEndWith' => 'mée',
                'idEqual' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-93', $rows);
        $this->assertEquals($this->variable('variable-93'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameEndWith' => 'mée',
                'idEqual' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-94', $rows);
        $this->assertEquals($this->variable('variable-94'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameEndWith' => 'mée',
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('endWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-95', $rows);
        $this->assertEquals($this->variable('variable-95'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameEndWith' => 'mée',
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('endWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-96', $rows);
        $this->assertEquals($this->variable('variable-96'), $rows);

        // dashed params
        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-end-with' => 'mée',
                'id-equal' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-93', $rows);
        $this->assertEquals($this->variable('variable-93'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-end-with' => 'mée',
                'id-equal' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-94', $rows);
        $this->assertEquals($this->variable('variable-94'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-end-with' => 'mée',
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('endWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-95', $rows);
        $this->assertEquals($this->variable('variable-95'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-end-with' => 'mée',
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('endWith')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-96', $rows);
        $this->assertEquals($this->variable('variable-96'), $rows);
    }

    public function testParamsContains()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameContains' => 'ie-thé',
                'idEqual' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-97', $rows);
        $this->assertEquals($this->variable('variable-97'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameContains' => 'ie-thé',
                'idEqual' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-98', $rows);
        $this->assertEquals($this->variable('variable-98'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameContains' => 'ie-thé',
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('contains')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-99', $rows);
        $this->assertEquals($this->variable('variable-99'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'firstNameContains' => 'ie-thé',
                'firstNameStartWith' => 'Pub',
                'idEqual' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('contains')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-100', $rows);
        $this->assertEquals($this->variable('variable-100'), $rows);

        // dashed params
        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-contains' => 'ie-thé',
                'id-equal' => 20
            ), array(
                'firstName' => 'all',
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-97', $rows);
        $this->assertEquals($this->variable('variable-97'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-contains' => 'ie-thé',
                'id-equal' => 20
            ), array(
                'firstName' => 1,
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-98', $rows);
        $this->assertEquals($this->variable('variable-98'), $rows);

        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-contains' => 'ie-thé',
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'operations' => array('contains')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-99', $rows);
        $this->assertEquals($this->variable('variable-99'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->columns(array(
                'id' => 'u.id',
                'firstName' => 'u.firstName',
                'countryId' => 'u.countryId',
            ))
            ->params(array(
                'first-name-contains' => 'ie-thé',
                'first-name-start-with' => 'Pub',
                'id-equal' => 20
            ), array(
                'firstName' => array(
                    'excluded' => array('contains')
                ),
            ))
            ->order('u.id asc')
            ->limit(5)
            ->fetch()
        ;

        // $this->createVariable('variable-100', $rows);
        $this->assertEquals($this->variable('variable-100'), $rows);
    }

    // public function testParamsNotLike()
    // {
    // }

    // public function testParamsLike()
    // {
    // }

    // public function testParamsNotEqual()
    // {
    // }

    public function testParamsEqual()
    {
        $this->initDatabase('bookshop');

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->order('u.id asc')
            ->params(array(
                'id' => 1,
            ), array(
                'id' => 1,
            ))
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-72'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->order('u.id asc')
            ->params(array(
                'idEqual' => 1,
            ), array(
                'id' => 'all',
            ))
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-72'), $rows);

        // ---------------
        $rows = (new Select($this->adapter('bookshop')))
            ->from('users', 'u')
            ->column('u.id')
            ->column('u.firstName')
            ->order('u.id asc')
            ->params(array(
                'id-equal' => 1,
            ), array(
                'id' => 'all',
            ))
            ->fetch()
        ;

        $this->assertEquals($this->variable('variable-72'), $rows);
    }

    // public function testParamsLowerThanEqual()
    // {
    // }

    // public function testParamsLowerThan()
    // {
    // }

    // public function testParamsGreaterThanEqual()
    // {
    // }

    // public function testParamsGreaterThan()
    // {
    // }

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

    public function testBetween()
    {

    }

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
