<?php
namespace Tests\Data;

use Tiie\Validators\RowExists;

class RowExistsTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $this->initDatabase('bookshop');

        $validator = new RowExists($this->getAdapter('bookshop'), 'users');

        $this->assertEquals(1, is_null($validator->validate(1)));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('5000')));
    }
}
