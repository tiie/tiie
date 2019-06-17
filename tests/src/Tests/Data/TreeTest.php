<?php
namespace Tests\Data;

use Tiie\Data\Tree;
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

        // $this->createVariable('variable-119', $tree->getLeafs());
        $this->assertEquals($this->getVariable('variable-119'), $tree->getLeafs());
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

        // $this->createVariable('variable-120', $tree->getPath(152));
        $this->assertEquals($this->getVariable('variable-120'), $tree->getPath(152));

        // $this->createVariable('variable-122', $tree->getPath(151));
        $this->assertEquals($this->getVariable('variable-122'), $tree->getPath(151));

        $this->assertEquals(null, $tree->getPath(109));
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
        $this->assertEquals(null, $tree->getPath(47));
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
        $this->assertEquals($this->getVariable('variable-121'), $tree->findById('151'));

        $this->assertEquals(null, $tree->findById('200'));
    }
}
