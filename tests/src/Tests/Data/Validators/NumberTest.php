<?php
namespace Tests\Data;

use Topi\Data\Validators\Number;

class NumberTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $a = "-1.20";

        var_export(is_int($a));
        echo "\n";

        var_export(is_int("{$a}"));
        echo "\n";

        var_export(is_float($a));
        echo "\n";

        var_export(is_float("{$a}"));
        echo "\n";

        var_export(is_numeric($a));
        echo "\n";

        var_export(is_numeric("{$a}"));
        echo "\n";

        var_export($a > PHP_INT_MAX);
        echo "\n";

        die('a');
        die(var_export(is_float(2147483648), true));

        // todo [debug] Debug to delete
        die(var_export(is_int(2147483647), true));

        // 2147483647
        // todo [debug] Debug to delete
        die(print_r(PHP_INT_MAX, true));
        // todo [debug] Debug to delete
        die(var_export(is_int(2147483647), true));

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

        $this->assertEquals(1, is_null($validator->validate(
            new class {
                public function __toString() {
                    return 10;
                }
            }
        )));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a-100')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a100')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(null)));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(array())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(new \stdClass())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('')));
    }
}
