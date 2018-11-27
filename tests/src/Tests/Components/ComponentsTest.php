<?php
namespace Tests;

use Tests\TestCase;
use Elusim\Components;
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

    // public function testGet()
    // {
    //     $components = $this->components();

    //     $this->assertEquals(Client::class, get_class($components->get('model.client')));
    // }

    public function testSelfDependentComponents()
    {
        $components = $this->components();

        $users = $components->get('model.users');

        $this->assertEquals($users->email(), $users->categories()->email());
        $this->assertEquals($users, $users->categories()->users());
    }

    public function testSelfDependentComponentRevert()
    {
        $components = $this->components();

        $categories = $components->get('model.users.categories');

        $this->assertEquals($categories->email(), $categories->users()->email());
        $this->assertEquals($categories, $categories->users()->categories());
    }
}
