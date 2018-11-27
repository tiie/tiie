<?php
namespace Tests\Http\Headers;

use Tests\TestCase;
use Elusim\Http\Headers\ContentType;

class TestContentType extends TestCase
{
    public function testMediaType()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $this->assertEquals('text/html', $contentType->mediaType());
    }

    public function testMediaTypeSet()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $contentType->mediaType('application/json');

        $this->assertEquals('application/json', $contentType->mediaType());
        $this->assertEquals('application/json; charset=utf-8; boundary=75', $contentType->value());
    }

    public function testCharset()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $this->assertEquals('utf-8', $contentType->charset());
    }

    public function testCharsetSet()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $contentType->charset('ascii');

        $this->assertEquals('ascii', $contentType->charset());
        $this->assertEquals('text/html; charset=ascii; boundary=75', $contentType->value());
    }

    public function testBoundary()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $this->assertEquals('75', $contentType->boundary());
    }

    public function testBoundarySet()
    {
        $contentType = new ContentType('text/html; charset=utf-8; boundary=75');

        $contentType->boundary('100');

        $this->assertEquals('100', $contentType->boundary());
        $this->assertEquals('text/html; charset=utf-8; boundary=100', $contentType->value());
    }
}
