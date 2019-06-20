<?php
namespace Tests\Router;

use Tests\TestCase;
use Tiie\Router\Router;
use Tiie\Http\Request;

class RouterTest extends TestCase
{
    public function testRun()
    {
        $router = new Router(array(
            "routes" => array(
                array(
                    "id" => "api",
                    "prefix" => "/api",
                    // "domain" => "api.ofiko.pl",

                    "map" => array(
                        array(
                            "id" => "api.users:get",
                            "urn" => "/users/{i:id}",
                            "method" => "get",
                            "action" => array(
                                "class" => "...",
                                "method" => "...",
                            ),
                        ),
                        array(
                            "id" => "api.users:post",
                            "urn" => "/users",
                            "method" => "post",
                            "action" => array(
                                "class" => "...",
                                "method" => "...",
                            ),
                        ),
                        array(
                            "id" => "api.users:delete",
                            "urn" => "/users/{i:id}",
                            "method" => "delete",
                            "action" => array(
                                "class" => "...",
                                "method" => "...",
                            ),
                        ),
                    ),
                ),
            ),
        ));

        $request = new Request("GET", "", array("search" => "jan"));

        // ---------------
        $request->setMethod("GET");
        $request->setUrn("/api/users/10");

        $router->prepare($request);
        
        $route = $router->getRoute();
        $group = $router->group();

        $this->assertEquals("api.users:get", $route->getId());
        $this->assertEquals("api", $group->getName());
        $this->assertEquals(array("id" => 10), $route->getParams());

        // ---------------
        $request->setMethod("POST");
        $request->setUrn("/api/users");

        $router->prepare($request);

        $route = $router->getRoute();
        $group = $router->group();

        $this->assertEquals("api.users:post", $route->getId());
        $this->assertEquals("api", $group->getName());
        $this->assertEquals(array(), $route->getParams());

        // ---------------
        $request->setMethod("DELETE");
        $request->setUrn("/api/users/10");

        $router->prepare($request);

        $route = $router->getRoute();
        $group = $router->group();

        $this->assertEquals("api.users:delete", $route->getId());
        $this->assertEquals("api", $group->getName());
        $this->assertEquals(array("id" => 10), $route->getParams());

        // ---------------
        $request->setMethod("GET");
        $request->setUrn("/api/users/10/10");

        $router->prepare($request);

        $route = $router->getRoute();
        $group = $router->group();

        $this->assertEquals(null, $route);
        $this->assertEquals("api", $group->getName());
    }
}
