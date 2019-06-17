<?php
namespace App\Actions;

class Clients
{
    public function get(\Tiie\Http\Request $request)
    {
        $response = new \Tiie\Response\Response();
        $response->setData(null);

        foreach ($this->getCollection() as $client) {
            if ($client['id'] == $request->getParam("id")) {
                $response->setData($client);
            }
        }

        return $response;
    }

    public function getCollection($params = array())
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
