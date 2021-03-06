<?php
namespace App\Actions;

use Tiie\Components\ComponentsTrait;
use Tiie\Http\Request;
use Tiie\Response\Response;

class Clients
{
    use ComponentsTrait;

    public function get(Request $request)
    {
        $response = new Response();
        $response->setData(null);

        foreach ($this->getCollection() as $client) {
            if ($client['id'] == $request->getParam("id")) {
                $response->setData($client);
            }
        }

        return $response;
    }

    public function getCollection(array $params = array())
    {
        return array(
            array(
                'id' => 1,
                'name' => 'Kasia'
            ),
            array(
                'id' => 2,
                'name' => 'Justyna'
            ),
            array(
                'id' => 3,
                'name' => 'Jolanta'
            ),
            array(
                'id' => 4,
                'name' => 'Dariusz'
            ),
            array(
                'id' => 5,
                'name' => 'Pusia'
            ),
        );
    }
}
