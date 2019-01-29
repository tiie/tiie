<?php
namespace Tests\Data;

use Tiie\Data\Validators\Tinyint;

class TinyintTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Tinyint();

        // TINYINT	1	-128	0	127	255

        $this->assertEquals(1, is_null($validator->validate(127)));
        $this->assertEquals(1, is_null($validator->validate(-128)));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('128')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-129')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a127')));

        $validator = new Tinyint(1);

        $this->assertEquals(1, is_null($validator->validate('127')));
        $this->assertEquals(1, is_null($validator->validate('128')));
        $this->assertEquals(1, is_null($validator->validate('255')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-128')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('256')));
    }
}
