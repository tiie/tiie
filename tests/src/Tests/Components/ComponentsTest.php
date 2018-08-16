<?php
namespace Tests;

use Tests\TestCase;
use Topi\Components;
use Tests\Components\Client;

class ComponentsTest extends TestCase
{
    private function components()
    {
        $components = new Components(array(
            'dirs' => array(
                __DIR__
            )
        ));

        return $components;
    }

    public function testGet()
    {
        $components = $this->components();

        $this->assertEquals(Client::class, get_class($components->get('model.client')));
    }
}
