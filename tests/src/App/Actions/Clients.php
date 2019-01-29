<?php
namespace App\Actions;

use Tiie\Actions\ActionInterface;

class Clients implements ActionInterface
{
    public function get(\Tiie\Http\Request $request)
    {
        $response = new \Tiie\Response\Response();
        $response->data(null);

        foreach ($this->collection() as $client) {
            if ($client['id'] == $request->param("id")) {
                $response->data($client);
            }
        }

        return $response;
    }

    public function collection($params = array())
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
