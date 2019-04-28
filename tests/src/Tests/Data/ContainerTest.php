<?php
namespace Tests\Data;

use Tiie\Data\Container;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    public function testGet()
    {
        $container = $this->initContainer();

        $this->assertEquals("1023234234", $container->get("id"));
        $this->assertEquals("John", $container->get("name"));

        $this->assertEquals(array(
            "id" => "10",
            "value" => "john@gmail.com"
        ), $container->get("email"));

        $this->assertEquals(array(
            array(
                "id" => "20",
                "value" => "098345456",
            ),
            array(
                "id" => "25",
                "value" => "098345456",
            ),
            array(
                "id" => "30",
                "value" => "098345456",
            ),
        ), $container->get("phones"));

        $this->assertEquals(null, $container->get("age"));
    }

    public function testSet()
    {
        $container = $this->initContainer();

        $this->assertEquals(null, $container->get("age"));

        $container->set("age", 20);

        $this->assertEquals(20, $container->get("age"));
    }

    public function testGetByArray()
    {
        $container = $this->initContainer();

        $this->assertEquals("1023234234", $container["id"]);
        $this->assertEquals("John", $container["name"]);

        $this->assertEquals(array(
            "id" => "10",
            "value" => "john@gmail.com"
        ), $container["email"]);

        $this->assertEquals(array(
            array(
                "id" => "20",
                "value" => "098345456",
            ),
            array(
                "id" => "25",
                "value" => "098345456",
            ),
            array(
                "id" => "30",
                "value" => "098345456",
            ),
        ), $container["phones"]);

        $this->assertEquals(null, $container["age"]);
    }

    public function testSetByArray()
    {
        $container = $this->initContainer();

        $this->assertEquals(null, $container["age"]);

        $container["age"] = 20;

        $this->assertEquals(20, $container["age"]);
    }

    public function testGetMagic()
    {
        $container = $this->initContainer();

        $this->assertEquals("1023234234", $container->id);
        $this->assertEquals("John", $container->name);

        $this->assertEquals(array(
            "id" => "10",
            "value" => "john@gmail.com"
        ), $container->email);

        $this->assertEquals(array(
            array(
                "id" => "20",
                "value" => "098345456",
            ),
            array(
                "id" => "25",
                "value" => "098345456",
            ),
            array(
                "id" => "30",
                "value" => "098345456",
            ),
        ), $container->phones);

        $this->assertEquals(null, $container->age);
    }

    public function testSetMagic()
    {
        $container = $this->initContainer();

        $this->assertEquals(null, $container->age);

        $container->age = 20;

        $this->assertEquals(20, $container->age);
    }

    public function testToArray()
    {
        $container = $this->initContainer();

        $this->assertEquals(array(
            "id" => "1023234234",
            "name" => "John",
            "email" => array(
                "id" => "10",
                "value" => "john@gmail.com"
            ),
            "phones" => array(
                array(
                    "id" => "20",
                    "value" => "098345456",
                ),
                array(
                    "id" => "25",
                    "value" => "098345456",
                ),
                array(
                    "id" => "30",
                    "value" => "098345456",
                ),
            ),
        ), $container->toArray());
    }

    public function testIs()
    {
        $container = $this->initContainer();
        $container
            ->set("countable", true)
            ->set("removeable", false)
        ;

        $this->assertEquals(true, $container->is("countable"));
        $this->assertEquals(false, $container->is("removeable"));
        $this->assertEquals(false, $container->is("array"));
    }

    public function testMerge()
    {
        $container = $this->initContainer();

        $container->merge(array(
            "age" => "20",
        ));

        $this->assertEquals(20, $container->get("age"));
    }

    private function initContainer()
    {
        return new Container(array(
            "id" => "1023234234",
            "name" => "John",
            "email" => array(
                "id" => "10",
                "value" => "john@gmail.com"
            ),
            "phones" => array(
                array(
                    "id" => "20",
                    "value" => "098345456",
                ),
                array(
                    "id" => "25",
                    "value" => "098345456",
                ),
                array(
                    "id" => "30",
                    "value" => "098345456",
                ),
            ),
        ));
    }
}
