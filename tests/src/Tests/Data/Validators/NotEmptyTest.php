<?php
namespace Tests\Data;

use Tiie\Validators\NotEmpty;

class NotEmptyTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new NotEmpty();

        $this->assertEquals(1, is_null($validator->validate(array(1))));
        $this->assertEquals(1, is_null($validator->validate('foo')));
        $this->assertEquals(1, is_null($validator->validate(0)));
        $this->assertEquals(1, is_null($validator->validate(10)));
        $this->assertEquals(1, is_null($validator->validate(-10)));
        $this->assertEquals(1, is_null($validator->validate(
            new class implements \Countable {
                public function count() {
                    return 1;
                }
            }
        )));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(array())));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate(
            new class implements \Countable {
                public function count() {
                    return 0;
                }
            }
        )));
    }
}
