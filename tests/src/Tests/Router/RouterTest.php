<?php
namespace Tests\Router;

class RouterTest extends \Tests\TestCase
{
    private function router()
    {
        $router = new \Tiie\Router\Router(array(
            "groups" => array(
                "api" => array(
                    "domain" => "{(pl|en):locale}.app.{i:version}.com",
                )
            ),

            'routes' => array(
                "api.users:get" => array(
                    "group" => "api",
                    "method" => "get",
                    "urn" => "/api/users/{i:id}",
                    "action" => array(
                        "class" => \App\Actions\Clients::class,
                    )
                ),
            )
        ));

        return $router;
    }

    public function testMatch()
    {
        $router = $this->router();

        $route = $router->match((new \Tiie\Http\Request("get", "/api/users/10"))
            ->domain("pl.app.10.com")
        );

        $this->assertEquals(array (
            'domain' => '{(pl|en):locale}.app.{i:version}.com',
            'group' => 'api',
            'method' => 'get',
            'urn' => '/api/users/{i:id}',
            'id' => 'api.users:get',
            'action' => array (
                'class' => 'App\\Actions\\Clients',
                'method' => 'get',
            ),
            'params' => array (
                'locale' => 'pl',
                'version' => '10',
                'id' => '10',
            ),
        ), $route);

        // not found
        $route = $router->match((new \Tiie\Http\Request("get", "/api/users/10"))
            ->domain("pl.app.10a.com")
        );

        $this->assertEquals(null, $route);
    }

    public function testRun()
    {
        $router = $this->router();

        $response = $router->run((new \Tiie\Http\Request("get", "/api/users/5"))
            ->domain("pl.app.10.com")
        );

        $this->assertEquals(array(
            'id' => 5,
            'name' => 'Pusia',
        ), $response->data());

        $response = $router->run((new \Tiie\Http\Request("get", "/api/users/10"))
            ->domain("pl.app.10.com")
        );

        $this->assertEquals(null, $response->data());
    }
}
