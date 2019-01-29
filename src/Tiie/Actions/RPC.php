<?php
namespace Tiie\Actions;

/**
 * Remote Procedure Call Action.
 */
abstract class RPC extends \Tiie\Actions\Action
{
    protected $headers = array();
    protected $code = null;

    public static function metadata()
    {
        return array(
            'description' => array(),

            // params for specific methods
            'params' => array(
                'get' => array(
                    // 'userId' => array(
                    //     'type' => 'string',
                    //     // 'required' => false,
                    //     // 'default' => null,

                    //     'validators' => array(
                    //         \Tiie\Validators\IsNumeric::class
                    //     ),
                    //     'filters' => array(),

                    //     'description' => array(
                    //         'pl' => 'Wybiera parametry dla danego użytkownika.',
                    //     ),
                    // )
                ),
            ),
            'fields' => array(
                // 'id' => array(
                //     'type' => 'int',
                //     // 'required' => true,
                //     // 'default' => null,
                //     // 'allowNull' => true,
                //     'validators' => array(),
                //     'filters' => array(),

                //     'description' => array(
                //         'pl' => 'Lista',
                //     ),
                // ),
                // 'id' => array(
                //     'type' => array(
                //         'source' => 'model',
                //         'source' => 'model',
                //     )
                // )
            ),
            'actions' => array(),
        );
    }

    public function action(\Tiie\Http\Request $request, array $params = array())
    {
        return $this->response($request, $this->run($request->id(), $request->params(), $request->input()));
    }

    public function run($id, array $params = array(), array $input = array())
    {
        return null;
    }

    /**
     * Metoda tworzy obiekt odpowiedzi na postawie przychodzących danych.
     *
     * @param array $data
     * @return \Tiie\Response\ResponseInterface
     */
    protected function response(\Tiie\Http\Request $request, array $data)
    {
        $response = new \Tiie\Response\Response($this);

        if (is_null($data)) {
            // Odpowiedź jest nieznana
            $response->code(404);
        }else{
            $response->data($data);
        }

        // Ustawiam resztę nagłówków
        foreach ($this->headers as $header => $value) {
            $response->header($header, $value);
        }

        return $response;
    }
}
