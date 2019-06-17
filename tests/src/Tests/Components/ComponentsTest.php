<?php
namespace Tests;

use Tests\TestCase;
use Tests\Components\Client;
use Tiie\Components\Supervisor as Components;

class ComponentsTest extends TestCase
{
    private function getComponents()
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
    //     $components = $this->getComponents();

    //     $this->assertEquals(Client::class, get_class($components->get('model.client')));
    // }

    public function testSelfDependentComponents()
    {
        $components = $this->getComponents();

        $users = $components->get('model.users');

        $this->assertEquals($users->getEmail(), $users->getCategories()->getEmail());
        $this->assertEquals($users, $users->getCategories()->getUsers());
    }

    public function testSelfDependentComponentRevert()
    {
        $components = $this->getComponents();

        $categories = $components->get('model.users.categories');

        $this->assertEquals($categories->getEmail(), $categories->getUsers()->getEmail());
        $this->assertEquals($categories, $categories->getUsers()->getCategories());
    }
}
