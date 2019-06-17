<?php
namespace Tests\Data\Model;

use Tests\TestCase;
use App\Models\Bookshop\Users;
use Tiie\Model\Records;

class ModelTest extends TestCase
{
    public function testFind()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->getAdapter('bookshop'));

        $records = $users->find(array(
            'id' => array(1, 2, 3),
            'order' => 'id asc',
        ));

        $this->assertEquals(true, $records instanceof Records);

        // $this->createVariable('variable-104', $records->toArray());
        $this->assertEquals($this->getVariable('variable-104'), $records->toArray());
    }

    // public function testGenerator()
    // {
    //     $this->initDatabase('bookshop');

    //     $users = new Users($this->getAdapter('bookshop'));

    //     foreach ($users->getGenerator() as $record) {
    //         // print_r($record);
    //     }
    // }

    public function testIterable()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->getAdapter('bookshop'));

        // get records
        $records = $users->find(array(
            'order' => 'id asc'
        ));

        $this->assertEquals(true, is_iterable($records));

        $recordsIds = array();

        foreach ($records as $key => $record) {
            $recordsIds[] = $record->get('id');

            if ($key == 4) {
                break;
            }
        }

        $this->assertEquals($this->getVariable('variable-105'), $recordsIds);
    }

    public function testFetch()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->getAdapter('bookshop'));

        // get records
        $rows = $users->fetch(array(
            'order' => 'id asc'
        ), array(), array(), 5);

        $this->assertEquals(true, is_array($rows));

        // $this->createVariable('variable-101', $rows);
        $this->assertEquals($this->getVariable('variable-101'), $rows);
    }

    public function testFetchById()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->getAdapter('bookshop'));

        // get records
        $rows = $users->fetchById(5);

        // $this->createVariable('variable-102', $rows);
        $this->assertEquals($this->getVariable('variable-102'), $rows);
    }

    public function testFetchByIds()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->getAdapter('bookshop'));

        // get records
        $rows = $users->fetchByIds(array(4, 5));

        // $this->createVariable('variable-103', $rows);
        $this->assertEquals($this->getVariable('variable-103'), $rows);
    }

    // public function testRun()
    // {

    // }

    // public function testValidate()
    // {

    // }

//     public function testSave()
//     {
//         $this->initDatabase('bookshop');
//
//         $users = new Users($this->getAdapter('bookshop'));
//
//         $record = $users->getRecord(5);
//         $record->set('firstName', 'changed');
//
//         $users->save($record);
//
//         $record = $users->getRecord(5);
//
//         $this->assertEquals('changed', $record->get('firstName'));
//         // $this->createVariable('variable-103', $rows);
//         // $this->assertEquals($this->getVariable('variable-103'), $rows);
//     }
//
    // public function testCreate()
    // {

    // }

    // public function testCreator()
    // {

    // }

    // public function testRemove()
    // {

    // }

    // public function testCreateRecord()
    // {

    // }

    // public function testRecords()
    // {

    // }

    // public function testRecord()
    // {

    // }
}
