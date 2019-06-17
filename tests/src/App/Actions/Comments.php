<?php
namespace App\Actions;

class Comments extends \Tiie\Actions\Rest {
    public static function getMetadata()
    {
        return array(
            'pipe' => array(
                'logs' => array(
                    'class' => \App\Actions\Logs::class,
                )
            ),
            'allowMethods' => array('get'),
        );
    }

    public function get($id, $params = array())
    {
        switch ($id) {
        case 'base':
            return array(
                'id' => 10,
                'comment' => 'comment 10',
            );

        default:
            return null;
        }
    }

    public function getCollection($params = array())
    {
        if (isset($params['cliendId'])) {
            switch ($params['cliendId']) {
            case '2':
                return array(
                    array(
                        'id' => 1,
                        'cliendId' => 2,
                        'comment' => 'Nice avatar'
                    ),
                    array(
                        'id' => 2,
                        'cliendId' => 2,
                        'comment' => 'Please contact with me'
                    ),
                );

            default:
                return array();
            }
        }else{
            return array();
        }
    }
}
