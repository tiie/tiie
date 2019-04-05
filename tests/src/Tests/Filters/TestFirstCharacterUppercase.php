<?php
namespace Tests\Filters;

use Tests\TestCase;
use Tiie\Filters\FirstCharacterUppercase;

class TestFirstCharacterUppercase extends TestCase
{
    public function testFilter()
    {
        $filter = new FirstCharacterUppercase;

        $this->assertEquals("Lorem ipsum", $filter->filter("lorem ipsum"));
    }
}
