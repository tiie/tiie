<?php
namespace Tests\Data;

use Tiie\Data\Validators\Number;

class NumberTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new Number();

        $this->assertEquals(1, is_null($validator->validate('-100')));
        $this->assertEquals(1, is_null($validator->validate('0')));
        $this->assertEquals(1, is_null($validator->validate('100')));
        $this->assertEquals(1, is_null($validator->validate('12432324234235325435435212312344543534543543534523432423432')));
        $this->assertEquals(1, is_null($validator->validate('-12432324234235325435435212312344543534543543534523432423432')));

        $this->assertEquals(1, is_null($validator->validate(-100)));
        $this->assertEquals(1, is_null($validator->validate(0)));
        $this->assertEquals(1, is_null($validator->validate(100)));
        $this->assertEquals(1, is_null($validator->validate('12432324234235325435435212312344543534543543534523432423432')));
        $this->assertEquals(1, is_null($validator->validate('-12432324234235325435435212312344543534543543534523432423432')));

        // $this->assertEquals(1, is_null($validator->validate(
        //     new class {
        //         public function __toString() {
        //             return 10;
        //         }
        //     }
        // )));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a-100')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a100')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(array())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(new \stdClass())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('')));
    }
}
