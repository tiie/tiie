<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Insert;
use Tiie\Data\Adapters\Commands\SQL\Expr;
use Tiie\Data\Adapters\Commands\Built;

// $this->createVariable('variable-38', $rows);
class InsertTest extends TestCase
{
    public function testInto()
    {
        $insert = new Insert();

        $this->assertEquals($insert, $insert->into('users'));
        $this->assertEquals('users', $insert->into());
    }

    // public function testTable()
    // {
    //     $insert = new Insert();

    //     $this->assertEquals($insert, $insert->table('users'));
    //     $this->assertEquals('users', $insert->table());
    // }

    public function testValues()
    {
        $insert = new Insert();

        $insert->setValues(array(
            array(
                'id' => 1,
                'name' => 'Pawel'
            ),
            array(
                'id' => 2,
                'name' => 'Pawel'
            ),
        ));

        // $this->createVariable('variable-63', $insert->getValues());
        $this->assertEquals($this->getVariable('variable-63'), $insert->getValues());

        // $this->assertEquals($insert, $insert->table('users'));
        // $this->assertEquals('users', $insert->table());
    }

    public function testValue()
    {
        $insert = new Insert();

        $insert->addValue(array(
            'id' => 1,
            'name' => 'Pawel'
        ));

        $insert->addValue(array(
            'id' => 2,
            'name' => 'Pawel'
        ));

        // $this->createVariable('variable-64', $insert->getValues());
        $this->assertEquals($this->getVariable('variable-64'), $insert->getValues());
    }

    public function testAdd()
    {
        $insert = new Insert();

        $insert->add(array(
            'id' => 1,
            'name' => 'Pawel'
        ));

        $this->assertEquals($insert, $insert->add(array(
            'id' => 2,
            'name' => 'Pawel'
        )));

        // $this->createVariable('variable-65', $insert->getValues());
        $this->assertEquals($this->getVariable('variable-65'), $insert->getValues());
    }

    public function testColumns()
    {
        $insert = new Insert();

        $this->assertEquals($insert, $insert->columns(array('id', 'name')));

        // $this->createVariable('variable-66', $insert->columns());
        $this->assertEquals($this->getVariable('variable-66'), $insert->columns());
    }

    public function testBuilt()
    {
        $insert = new Insert();

        $insert->into('users');
        $insert->columns(array('id', 'name'));
        $insert->setValues(array(
            array(
                'id' => 1,
                'name' => 'Pawel',
            )
        ));

        $this->assertEquals(true, $insert->build() instanceof Built);
    }

    public function testCreateRow()
    {
        $this->initDatabase('bookshop');

        $insert = new Insert($this->getAdapter('bookshop'));

        $insert->into('users')
            ->add(array(
                // 'id' => 19,
                'firstName' => 'Illustrée',
                'lastName' => 'Sauvage',
                'email' => 'jsauvagei@parallels.com',
                'genderId' => 257,
                'birthDate' => '0000-00-00',
                'ip' => '152.106.13.28',
                'countryId' => NULL,
                'cityId' => 1134,
                'phone' => '501-972-3966',
            ))
            ->execute()
        ;

        $id = $this->getAdapter('bookshop')->lastId();

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
            ->equal('id', $id)
            ->fetch()
            ->getData()
        ;

        $this->assertEquals(1, count($rows));

        // $this->createVariable('variable-67', $rows[0]);
        $this->assertEquals($this->getVariable('variable-67'), $rows[0]);
    }

    public function testCreateRowWithId()
    {
        $this->initDatabase('bookshop');

        $insert = new Insert($this->getAdapter('bookshop'));

        $insert->into('users')
            ->add(array(
                'id' => 3000,
                'firstName' => 'Illustrée',
                'lastName' => 'Sauvage',
                'email' => 'jsauvagei@parallels.com',
                'genderId' => 257,
                'birthDate' => '0000-00-00',
                'ip' => '152.106.13.28',
                'countryId' => NULL,
                'cityId' => 1134,
                'phone' => '501-972-3966',
            ))
            ->execute()
        ;

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
            ->equal('id', 3000)
            ->fetch()
            ->getData()
        ;

        $this->assertEquals(1, count($rows));

        // $this->createVariable('variable-68', $rows[0]);
        $this->assertEquals($this->getVariable('variable-68'), $rows[0]);
    }
}
