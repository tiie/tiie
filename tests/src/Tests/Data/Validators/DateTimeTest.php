<?php
namespace Tests\Data;

use Elusim\Data\Validators\DateTime;

class DateTimeTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new DateTime();

        $this->assertEquals(1, is_null($validator->validate('2010-12-23 10:12:11')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010-13-23 10:12')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a2010-13-23 10:12:11')));

        $validator = new DateTime('Y/m/d H:i');

        $this->assertEquals(1, is_null($validator->validate('2010/12/23 12:00')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010/13/23 10:')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010-13/23 10:12')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
    }
}
