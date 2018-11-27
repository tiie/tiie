<?php
namespace Tests\Data;

use Elusim\Data\Validators\Date;

class DateTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Date();

        $this->assertEquals(1, is_null($validator->validate('2010-12-23')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010-13-23')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a2010-13-23')));

        $validator = new Date('Y/m/d');

        $this->assertEquals(1, is_null($validator->validate('2010/12/23')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010/13/23')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('2010-13/23')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
    }
}
