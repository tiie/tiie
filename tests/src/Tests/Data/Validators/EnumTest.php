<?php
namespace Tests\Data;

use Tiie\Data\Validators\Enum;

class EnumTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $objectA = new \stdClass();
        $objectB = new \stdClass();

        $validator = new Enum(array(1, 'b', $objectA));

        $this->assertEquals(1, is_null($validator->validate(1)));
        $this->assertEquals(1, is_null($validator->validate('b')));
        $this->assertEquals(1, is_null($validator->validate($objectA)));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(2)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('c')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate($objectB)));

        $validator = new Enum(array(1, 'b', null));

        $this->assertEquals(1, is_null($validator->validate(1)));
        $this->assertEquals(1, is_null($validator->validate('b')));
        $this->assertEquals(1, is_null($validator->validate(null)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate($objectA)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate($objectB)));
    }
}
