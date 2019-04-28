<?php
namespace App\Actions\Clients;

class CreateClient
{
    public static function metadata()
    {
        return array(
            'pipe' => array(
                'activation' => array(
                    'class' => \App\Actions\Clients\Activation::class,
                    'map' => array(
                        'id' => 'id',
                        'clientTree' => 'tree',
                    )
                )
            ),
        );
    }

    public function run($id, array $params = array(), array $input = array())
    {
        return array(
            'id' => "10",
            'clientTree' => "yes",
        );
    }
}
