<?php
namespace Tests\Data;

use Elusim\Data\Validators\Exists;

class ExistsTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Exists('name');

        $this->assertEquals(1, is_null($validator->validate(array(
            'id' => 10,
            'name' => 'Foo',
        ))));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(array(
            'id' => 10,
            'fullName' => 'Foo',
        ))));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
    }
}
