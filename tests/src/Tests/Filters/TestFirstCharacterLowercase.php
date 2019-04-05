<?php
namespace Tests\Filters;

use Tests\TestCase;
use Tiie\Filters\FirstCharacterLowercase;

class TestFirstCharacterLowercase extends TestCase
{
    public function testFilter()
    {
        $filter = new FirstCharacterLowercase;

        $this->assertEquals("lorem ipsum", $filter->filter("Lorem ipsum"));
    }
}
