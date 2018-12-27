<?php
namespace Tests\Data;

use Elusim\Data\Tree;
use Tests\TestCase;

use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\Error\Warning;

class TreeTest extends TestCase
{
    public function testLeafs()
    {
        $tree = new Tree(array(
            array(
                'id' => 1,
                'parentId' => null,
            ),
                array(
                    'id' => 12,
                    'parentId' => 1,
                ),
                array(
                    'id' => 13,
                    'parentId' => 1,
                ),
                array(
                    'id' => 14,
                    'parentId' => 1,
                ),
                array(
                    'id' => 15,
                    'parentId' => 1,
                ),
                    array(
                        'id' => 151,
                        'parentId' => 15,
                    ),
                    array(
                        'id' => 152,
                        'parentId' => 15,
                    ),
        ));

        // $this->createVariable('variable-119', $tree->leafs());
        $this->assertEquals($this->variable('variable-119'), $tree->leafs());
    }

    public function testPath()
    {
        $tree = new Tree(array(
            array(
                'id' => 1,
                'parentId' => null,
            ),
                array(
                    'id' => 12,
                    'parentId' => 1,
                ),
                array(
                    'id' => 13,
                    'parentId' => 1,
                ),
                array(
                    'id' => 14,
                    'parentId' => 1,
                ),
                array(
                    'id' => 15,
                    'parentId' => 1,
                ),
                    array(
                        'id' => 151,
                        'parentId' => 15,
                    ),
                    array(
                        'id' => 152,
                        'parentId' => 15,
                    ),
            array(
                'id' => 45,
                'parentId' => 44,
            ),
                array(
                    'id' => 46,
                    'parentId' => 45,
                ),
                    array(
                        'id' => 47,
                        'parentId' => 46,
                    ),
        ));

        // $this->createVariable('variable-120', $tree->path(152));
        $this->assertEquals($this->variable('variable-120'), $tree->path(152));

        // $this->createVariable('variable-122', $tree->path(151));
        $this->assertEquals($this->variable('variable-122'), $tree->path(151));

        $this->assertEquals(null, $tree->path(109));
    }

    public function testPathInconsistent()
    {
        $tree = new Tree(array(
            array(
                'id' => 1,
                'parentId' => null,
            ),
                array(
                    'id' => 12,
                    'parentId' => 1,
                ),
                array(
                    'id' => 13,
                    'parentId' => 1,
                ),
                array(
                    'id' => 14,
                    'parentId' => 1,
                ),
                array(
                    'id' => 15,
                    'parentId' => 1,
                ),
                    array(
                        'id' => 151,
                        'parentId' => 15,
                    ),
                    array(
                        'id' => 152,
                        'parentId' => 15,
                    ),
            array(
                'id' => 45,
                'parentId' => 44,
            ),
                array(
                    'id' => 46,
                    'parentId' => 45,
                ),
                    array(
                        'id' => 47,
                        'parentId' => 46,
                    ),
        ));

        $this->expectException(Notice::class);

        // Inconsistent tree.
        $this->assertEquals(null, $tree->path(47));
    }

    public function testFindById()
    {
        $tree = new Tree(array(
            array(
                'id' => 1,
                'parentId' => null,
            ),
                array(
                    'id' => 12,
                    'parentId' => 1,
                ),
                array(
                    'id' => 13,
                    'parentId' => 1,
                ),
                array(
                    'id' => 14,
                    'parentId' => 1,
                ),
                array(
                    'id' => 15,
                    'parentId' => 1,
                ),
                    array(
                        'id' => 151,
                        'parentId' => 15,
                    ),
                    array(
                        'id' => 152,
                        'parentId' => 15,
                    ),
        ));

        // $this->createVariable('variable-121', $tree->findById('151'));
        $this->assertEquals($this->variable('variable-121'), $tree->findById('151'));

        $this->assertEquals(null, $tree->findById('200'));
    }
}
