<?php
namespace Tests\Http\Headers;

use Tests\TestCase;
use Tiie\Http\Headers\Parser;

class TestParser extends TestCase
{
    public function testParse()
    {
        $parser = new Parser();

        $headers = $parser->parse($this->getVariable('variable-106'));

        // todo Extends unit test for rest headers.
        $this->assertEquals('application/json', $headers->get('Content-Type')->mediaType());
    }
}
