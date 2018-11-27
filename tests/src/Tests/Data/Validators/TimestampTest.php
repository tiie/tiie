<?php
namespace Tests\Data;

use Elusim\Data\Validators\Timestamp;

class TimestampTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Timestamp();

        $this->assertEquals(1, is_null($validator->validate("0")));
        $this->assertEquals(1, is_null($validator->validate("1231312")));
        $this->assertEquals(1, is_null($validator->validate(1231312)));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('0')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-1231312')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(-1231312)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(new \stdClass())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(array())));
    }
}
