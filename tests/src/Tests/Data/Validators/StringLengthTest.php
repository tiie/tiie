<?php
namespace Tests\Data;

use Tiie\Validators\StringLength;

class StringLengthTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new StringLength(5);

        $this->assertEquals(1, is_null($validator->validate("")));
        $this->assertEquals(1, is_null($validator->validate("a")));
        $this->assertEquals(1, is_null($validator->validate("ab")));
        $this->assertEquals(1, is_null($validator->validate("abc")));
        $this->assertEquals(1, is_null($validator->validate("abcd")));
        $this->assertEquals(1, is_null($validator->validate("abcde")));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('abcdef')));

        $validator = new StringLength(5, 3);

        $this->assertEquals(1, is_null($validator->validate("abc")));
        $this->assertEquals(1, is_null($validator->validate("abcd")));
        $this->assertEquals(1, is_null($validator->validate("abcde")));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('ab')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('abcdef')));
    }
}
