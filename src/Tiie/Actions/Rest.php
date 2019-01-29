<?php
namespace Tiie\Actions;

abstract class Rest extends \Tiie\Actions\Action
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
                'post' => array(),
                'put' => array(),
                'delete' => array(),
                'options' => array(),
            ),
            // description of data for post and put
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

            // childs actions
            'actions' => array(),
            'allowMethods' => array('get', 'post', 'put', 'delete'),
        );
    }

    public function action(\Tiie\Http\Request $request, array $params = array())
    {
        // sprawdzam czy metota jest dozwolona
        $metadata = static::metadata();

        if (!in_array($request->method(), $metadata['allowMethods']) && $request->method() != 'options') {
            throw new \Tiie\Exceptions\Http\MethodNotAllowed();
        }

        switch ($request->method()) {
        case 'get':
            if (!empty($request->id())){
                return $this->getAction($request);
            }else{
                return $this->collectionAction($request);
            }
        case 'post':
            return $this->postAction($request);
        case 'put':
            return $this->putAction($request);
        case 'delete':
            return $this->deleteAction($request);
        case 'options':
            return $this->optionsAction($request);
        }
    }

    public function getAction(\Tiie\Http\Request $request)
    {
        return $this->response($request, $this->get($request->id(), $request->params()));
    }

    public function get($id, $params = array())
    {
        throw new \Tiie\Exceptions\Http\MethodNotAllowed();
    }

    // collection
    public function collectionAction(\Tiie\Http\Request $request)
    {
        return $this->response($request, $this->collection($request->params()));
    }

    public function collection($params = array())
    {
        throw new \Tiie\Exceptions\Http\MethodNotAllowed();
    }

    // post
    public function postAction(\Tiie\Http\Request $request)
    {
        $data = $this->post($request->input(), $request->params());

        $response = new \Tiie\Response\Response($this);

        if (is_null($this->code)) {
            if (is_null($data)) {
                // todo Aliasy na kody odpowidzi.
                // Można by dodać kody w formie stringów do metody code które
                // byłyby tłumaczone np.
                // $response->code('@created');
                $response->code(204);
            }else{
                $response->code(201);
            }
        }else{
            $response->code($this->code);
        }

        $response->data($data);

        foreach ($this->headers as $header => $value) {
            $response->header($header, $value);
        }

        return $response;
    }

    protected function header($name, $value = null)
    {
        if (is_null($value)) {
            if (array_key_exists($name, $this->headers)) {
                return $this->headers[$name];
            }else{
                return null;
            }
        }else{
            $this->headers[$name] = $value;

            return $this;
        }
    }

    protected function headers($headers = null)
    {
        if (is_null($headers)) {
            return $this->headers;
        }else{
            $this->headers = $headers;

            return $this;
        }
    }

    protected function code($code = null)
    {
        if (is_null($code)) {
            return $this->code;
        }else{
            $this->code = $code;

            return $this;
        }
    }


    public function post($data = array(), $params = array())
    {
        throw new \Tiie\Exceptions\Http\MethodNotAllowed();
    }

    // put
    public function putAction(\Tiie\Http\Request $request)
    {
        return $this->response($request, $this->put($request->id(), $request->input(), $request->params()));
    }

    public function put($id, $data = array(), $params = array())
    {
        throw new \Tiie\Exceptions\Http\MethodNotAllowed();
    }

    // delete
    public function deleteAction(\Tiie\Http\Request $request)
    {
        return $this->response($request, $this->delete($request->id(), $request->params()));
    }

    public function delete($id, $params = array())
    {
        throw new \Tiie\Exceptions\Http\MethodNotAllowed();
    }

    //
    public function optionsAction(\Tiie\Http\Request $request)
    {
        $response = new \Tiie\Response\Response($this);

        // get metadata
        $metadata = static::metadata();
        $this->headers['Access-Control-Allow-Methods'] = strtoupper(implode(',', $metadata['allowMethods']));

        $p = $this->prepare(array(), $request->params(), 'options');

        return $this->response($request, $this->options($request->params()));
    }

    public function options($params = array())
    {
        return static::metadata();
    }

    /**
     * Metoda na wejsciu otrzymuje dane wajsciowe które następnie
     *
     * @return array Zwrocona powinna zostać tablica -
     * array(
     *     'data' => array(),
     *     'params' => array(),
     * )
     */
    protected function prepare($data = array(), $params = array(), $method)
    {
        return array($params, $data);
    }

    /**
     * Ustawia nadglowki zwiazane z iloscia wierszy, strona i wielkoscia
     * strony.
     *
     * @param int $rowsNumber
     * @param int $page
     * @param int $pageSize
     * @return $this
     */
    public function counter($rowsNumber, $page = null, $pageSize = null)
    {
        if (is_array($page)) {
            if (isset($page['page']) && isset($page['pageSize'])) {
                return $this->counter($rowsNumber, $page['page'], $page['pageSize']);
            }
        }

        $this->headers['X-Rows-Number'] = $rowsNumber;

        if (is_numeric($page) && is_numeric($pageSize)) {
            if ($pageSize > 0 && $page >= 0) {
                $this->headers['X-Rows-Number'] = $rowsNumber;
                $this->headers['X-Page-Size'] = $pageSize;
                $this->headers['X-Page'] = $page;
                $this->headers['X-Page-Offset'] = $page * $pageSize;
                $this->headers['X-Pages-Number'] = ceil($rowsNumber / $pageSize);
            }
        }

        return $this;
    }

    /**
     * Obsługa odpowiedzi
     */
    protected function response(\Tiie\Http\Request $request, array $data = null)
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
