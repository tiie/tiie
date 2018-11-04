<?php
namespace Tests\Data;

use Topi\Data\Validators\Mediumint;

class MediumintTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Mediumint();

        // 8388607
        // -8388608
        // 16777215

        $this->assertEquals(1, is_null($validator->validate('8388607')));
        $this->assertEquals(1, is_null($validator->validate('-8388608')));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('8388608')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-8388609')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a8388609')));

        $validator = new Mediumint(1);

        $this->assertEquals(1, is_null($validator->validate('8388607')));
        $this->assertEquals(1, is_null($validator->validate('8388608')));
        $this->assertEquals(1, is_null($validator->validate('16777215')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-8388608')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('16777216')));
    }
}
