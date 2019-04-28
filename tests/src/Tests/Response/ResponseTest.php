<?php
namespace Tests\Router;

class ResponseTest extends \Tests\TestCase
{
    // public function testTwig()
    // {
    //     $this->app();

    //     $request = new \Tiie\Http\Request("get", "/api/clients/2");

    //     $response = $app->component("@router")->run($request);

    //     // Ustawiam silnik odpowiedzi
    //     $response->engine("@response.engines.twig");

    //     // die(print_r($response->response($request), 1));
    // }

    public function testPrepare()
    {
        // $this->app();

        $response = new \Tiie\Response\Response();

        $response->variable("name", "Paweł");
        $response->variable("age", 12);
        $response->variable("contacts", array(
            array(
                'id' => 1,
                'phone' => '722397244'
            ),
            array(
                'id' => 2,
                'phone' => '987397244'
            ),
            array(
                'id' => 3,
                'phone' => '354397244'
            ),
        ));

        $response->variable("email", array(
            'id' => 89,
            'email' => 'bobryk.pawel@gmail.com'
        ));

        $variable = $this->variable('variable-7');
        $prepared = $response->prepare("html.javascript.variables");
        $this->md5($variable['prepared']);

        $this->assertEquals($this->string($variable['prepared']), $this->string($prepared));
    }
}
