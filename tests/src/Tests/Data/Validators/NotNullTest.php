<?php
namespace Tests\Data;

use Topi\Data\Validators\NotNull;

class NotNullTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new NotNull();

        $this->assertEquals(1, is_null($validator->validate(1)));
        $this->assertEquals(1, is_null($validator->validate('foo')));
        $this->assertEquals(1, is_null($validator->validate(new \stdClass())));
        $this->assertEquals(1, is_null($validator->validate(array())));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
    }
}
