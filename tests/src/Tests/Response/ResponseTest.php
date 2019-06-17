<?php
namespace Tests\Router;

class ResponseTest extends \Tests\TestCase
{
    // public function testTwig()
    // {
    //     $this->getApp();

    //     $request = new \Tiie\Http\Request("get", "/api/clients/2");

    //     $response = $app->getComponent("@router")->run($request);

    //     // Ustawiam silnik odpowiedzi
    //     $response->setEngine("@response.engines.twig");

    //     // die(print_r($response->response($request), 1));
    // }

    public function testPrepare()
    {
        // $this->getApp();

        $response = new \Tiie\Response\Response();

        $response->setVariable("name", "Paweł");
        $response->setVariable("age", 12);
        $response->setVariable("contacts", array(
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

        $response->setVariable("email", array(
            'id' => 89,
            'email' => 'bobryk.pawel@gmail.com'
        ));

        $variable = $this->getVariable('variable-7');
        $prepared = $response->prepare("html.javascript.variables");
        $this->md5($variable['prepared']);

        $this->assertEquals($this->string($variable['prepared']), $this->string($prepared));
    }
}
