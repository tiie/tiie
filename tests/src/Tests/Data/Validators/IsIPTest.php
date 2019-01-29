<?php
namespace Tests\Data;

use Tiie\Data\Validators\IsIP;

class IsIPTest extends \Tests\TestCase
{
    public function testValidate()
    {
        $validator = new IsIP();

        $this->assertEquals(1, is_null($validator->validate('127.0.0.1')));
        $this->assertEquals(1, is_null($validator->validate('255.255.255.255')));
        $this->assertEquals(1, is_null($validator->validate('0.0.0.0')));

        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('255.255.255.256')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('0.0.0.-1')));
        $this->assertArraySubset(array('code', 'error'), array_keys($validator->validate('a.0.0.0')));
    }
}
