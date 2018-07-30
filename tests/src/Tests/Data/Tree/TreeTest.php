<?php
namespace Tests\Data;

class TreeTest extends \Tests\TestCase
{
    public function testSimpleTree()
    {
        $tree = new \Topi\Data\Tree\Tree($this->variable('variable-12'));

        $this->assertEquals($this->variable("variable-13"), $tree->path(59));
        $this->assertEquals($this->variable("variable-14"), $tree->tree(40));
        $this->assertEquals($this->variable("variable-15"), $tree->tree(40, 'flat'));
    }
}
