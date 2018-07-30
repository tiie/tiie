<?php
namespace App\Actions\Clients;

class Activation extends \Topi\Actions\RPC
{
    public function run($id, array $params = array(), array $input = array())
    {
        return array(
            'status' => 'success',
            'id' => $params['id'],
            'tree' => $params['tree'],
        );
    }
}
