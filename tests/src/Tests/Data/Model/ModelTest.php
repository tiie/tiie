<?php
namespace Tests\Data\Model;

use Tests\TestCase;
use App\Models\Bookshop\Users;

class ModelTest extends TestCase
{
    // public function testFind()
    // {
    //     $this->initDatabase('bookshop');

    //     $users = new Users($this->adapter('bookshop'));

    //     $records = $users->find(array(
    //         'id' => array(1, 2, 3)
    //     ));

    // }

    // public function testGenerator()
    // {
    //     $this->initDatabase('bookshop');

    //     $users = new Users($this->adapter('bookshop'));

    //     foreach ($users->generator() as $record) {
    //         // print_r($record);
    //     }
    // }

    public function testIterable()
    {
        $this->initDatabase('bookshop');

        $users = new Users($this->adapter('bookshop'));

        foreach ($users->find() as $key => $record) {
            print_r($record);

            if ($key == 5) {
                break;
            }
        }
    }

    // public function testFetch()
    // {

    // }

    // public function testFetchById()
    // {

    // }

    // public function testFetchByIds()
    // {

    // }

    // public function testRun()
    // {

    // }

    // public function testValidate()
    // {

    // }

    // public function testSave()
    // {

    // }

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
