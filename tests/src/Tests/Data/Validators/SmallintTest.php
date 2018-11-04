<?php
namespace Tests\Data;

use Topi\Data\Validators\Smallint;

class SmallintTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Smallint();

        // SMALLINT	2	-32768	0	32767	65535

        $this->assertEquals(1, is_null($validator->validate(32767)));
        $this->assertEquals(1, is_null($validator->validate(-32768)));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('32768')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-32769')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a32767')));

        $validator = new Smallint(1);

        $this->assertEquals(1, is_null($validator->validate('32767')));
        $this->assertEquals(1, is_null($validator->validate('32769')));
        $this->assertEquals(1, is_null($validator->validate('65535')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('-32768')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('65536')));
    }
}
