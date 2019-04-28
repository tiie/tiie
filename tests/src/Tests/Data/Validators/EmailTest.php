<?php
namespace Tests\Data;

use Tiie\Validators\Email;

class EmailTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Email();

        $this->assertEquals(1, is_null($validator->validate('angel@gmail.com')));
        $this->assertEquals(1, is_null($validator->validate('angel.belly@gmail.com')));
        $this->assertEquals(1, is_null($validator->validate('angel-belly@gmail.com')));
        $this->assertEquals(1, is_null($validator->validate('angel_belly@gmail.com')));
        $this->assertEquals(1, is_null($validator->validate('angel#belly@gmail.com')));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('angel@gmail')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('angel')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('@gmail.com')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
    }
}
