<?php
namespace Topi\Router;

class Router
{
    use \Topi\ComponentsTrait;

    const OBJECT_ROUTE = 'route';
    const OBJECT_ACTION = 'action';

    private $config;

    /**
     * @var \Topi\Config Reference to params object.
     */
    private $params;

    /**
     * Ostatni aktywny route
     */
    private $route;

    function __construct($params = array())
    {
        $this->config = $this->component("@config")->get("topi.router");

        $this->params = array_replace_recursive(array(
            'error' => array(
                'action' => null,
            ),
            'routes' => array(
                // 'api' => array(
                //     'prefix' => '/api',
                //     'dir' => "../src/Actions",
                //     'namespace' => "\\App\\Actions",
                // ),
            ),

        ), $this->config);
    }

    /**
     * Szuka określonego elementu na postawie podanych parametrów.
     *
     * @param string $object
     * @param string $params
     */
    public function find($object, array $params = array())
    {
        switch ($object) {
        case 'route':
            foreach ($this->params['routes'] as $id => $row) {
                if (!empty($params['id'])) {
                    if ($id == $params['id']) {
                        return $row;
                    }

                }

                if (!empty($params['urn'])) {
                    if (strpos($params['urn'], $row['urn']) === 0) {

                        return $row;
                    }
                }
            }

            return null;

        case 'action':
            if (empty($params['urn'])) {
                return null;
            }

            $route = $this->find('route', array(
                'urn' => $params['urn']
            ));

            if (is_null($route)) {
                return null;
            }

            $resource = str_replace($route['urn'], '', $params['urn']);
            $resolved = $this->resolve($resource);

            $class = $route['namespace'];;

            foreach ($resolved as $key => $element) {
                $class = $class."\\".ucfirst($element['value']);

                if (isset($element['id'])) {
                    unset($resolved[$key]);

                    break;
                }else{
                    unset($resolved[$key]);
                }
            }

            if (!class_exists($class)) {
                return null;
            }

            if (!empty($resolved)) {
                // Jeśli zostały jeszcze parametry to oznacza jakies pod
                // akcje.
                foreach ($resolved as $key => $element) {
                    $metadata = $class::metadata();

                    if (!empty($metadata['pipe'][$element['value']])) {
                        $class = "\\".$metadata['pipe'][$element['value']]['class'];

                        if (!class_exists($class)) {
                            return null;
                        }
                    }else{
                        return null;
                    }
                }
            }

            return $class;
        }
    }

    /**
     * Podaną ścieżkę zamienia na liste nazw z parametrem id jeśli jest podany.
     */
    private function resolve($urn)
    {
        $resolved = array();
        $urn = explode('/', trim($urn, '/ '));
        $i = -1;

        foreach ($urn as $value) {
            if (is_numeric($value)) {
                $resolved[$i]['id'] = $value;
            }elseif($value[0] == ':'){
                $resolved[$i]['id'] = substr($value, 1);
            }else{
                $resolved[] = array(
                    'value' => $value
                );

                $i++;
            }
        }

        return $resolved;
    }

    /**
     * Run action with given request
     *
     * @param \Topi\Http\Request
     * @throws \Topi\Router\Exceptions\ActionNotFound
     * @return \Topi\Response\ResponseInterface or null if action not found
     */
    public function run(\Topi\Http\Request $request)
    {
        $route = $this->find('route', array(
            'urn' => $request->urn()
        ));

        if (is_null($route)) {
            throw new \Topi\Router\Exceptions\ActionNotFound();
        }

        // Usuwam prefix
        $resource = str_replace($route['urn'], '', $request->urn());
        $resolved = $this->resolve($resource);

        // Zliczam liczbe elementów
        $count = count($resolved);

        // /api/clients
        // /api/clients/10
        // /api/clients/10/comments
        // /api/clients/10/comments/20
        // /api/clients/10/comments/20/logs
        // /api/clients/10/comments/20/logs/10
        // /api/offers/activations

        // Ostatnia odpowiedź
        $lastResponse = array();
        $lastAction = array();
        $lastMetadata = array();
        $pipe = 0;

        $class = $route['namespace'];

        foreach ($resolved as $key => $element) {
            $class = $class."\\".ucfirst($element['value']);

            if ($pipe) {
                // Musimy zainicjować nowy Pipe, czyli sprawdzam czy w
                // statnich metadanych jest ta akcja
                if (!isset($lastMetadata['pipe'][$element['value']])) {
                    // Taka akcja nie istnieje.
                    throw new \Topi\Router\Exceptions\ActionNotFound();
                }

                // Wczytujemy ostatnią akcje.
                $lastAction = $lastMetadata['pipe'][$element['value']];

                // Wczytujemy klasę dla ostatniej akcji
                $class = "\\".$lastAction['class'];

                // Zmieniamy pipe na 0
                $pipe = 0;
            }

            if (isset($element['id'])) {
                if ($key == $count - 1) {
                    // Ostatni element z przekazaniem id
                    // /api/clients/10
                    //      ----------
                    $params = array();

                    if (!is_null($lastResponse)) {
                        if (!empty($lastAction['map'])) {
                            foreach ($lastAction['map'] as $from => $to) {
                                if (array_key_exists($from, $lastResponse)) {
                                    // Przekazuje parametry z odpowiedzi
                                    $request->param($to, $lastResponse[$from]);
                                }
                            }
                        }

                        $lastResponse = null;
                        $lastAction = null;
                    }

                    $request->id($element['id']);

                    // Zwracam to co zwróci mi akcja
                    return (new $class())->action($request);
                }else{
                    // Mamy ID ale nie jest to ostatni element. Wtedy muszę
                    // przełączyć się na nową klasę
                    // /api/clients/10/comments
                    //      -----------

                    if (!class_exists($class)) {
                        throw new \Topi\Router\Exceptions\ActionNotFound();
                    }

                    $action = new $class();

                    $lastResponse = null;

                    if ($action instanceof \Topi\Actions\RPC) {
                        $lastResponse = $action->run($element['id']);
                    }elseif($action instanceof \Topi\Actions\Rest){
                        $lastResponse = $action->get($element['id']);
                    }

                    // Pobieram metadane
                    $lastMetadata = $class::metadata();

                    if (is_null($lastResponse)) {
                        // Pusta odpowiedź, nie ma sensu dalej
                        // przekierowywać.
                        return null;
                    }

                    // Mamy jakąś odpowiedź, teraz ustawiam pipe, czyli w
                    // następnym kroku nastąpi przejście do nowej klasy
                    $pipe = 1;
                }
            }else{
                if ($key == $count - 1) {
                    // Jest to ostatni element

                    if (!class_exists($class)) {
                        throw new \Topi\Router\Exceptions\ActionNotFound();
                    }

                    if (!is_null($lastResponse)) {
                        if (!empty($lastAction['map'])) {
                            foreach ($lastAction['map'] as $from => $to) {
                                if (array_key_exists($from, $lastResponse)) {
                                    // Przekazuje parametry z odpowiedzi
                                    $request->param($to, $lastResponse[$from]);
                                }
                            }
                        }

                        $lastResponse = null;
                        $lastAction = null;
                    }

                    return (new $class())->action($request);
                }
            }
        }
    }

    public function routes()
    {
        return $this->params['routes'];
    }

    /**
     * Uruchamia akcje odpowiedzialną za obsługę błędów. W pierwszym kroku
     * sprawdza ostani aktywny routing. Jeśli taki jest, wywołuje akcje error
     * dla tego routingu.
     *
     * Jeśli nie ma podanej akcji do obsługi błędu w routingu, to wywołuje główna
     * klasę do obsługi błędów - jeśli ta nie jest podana, to wyrzuca wyjątek.
     *
     * @param mixed $error
     * @param \Topi\Http\Request $request
     *
     * @throws \Topi\Router\Exceptions\ErrorActionNotDefined
     *
     * @return \Topi\Response\ResponseInterface
     */
    public function error($error, $request)
    {
        $class = null;

        if (!is_null($this->route)) {
            if (!empty($this->route['error']['action'])) {
                $class = $this->route['error']['action'];
            }
        }

        if (is_null($class)) {
            // Klasa cały czas nie została określona.
            if (!empty($this->params['error']['action'])) {
                $class = $this->params['error']['action'];
            }
        }

        if (is_null($class)) {
            throw new \Topi\Router\Exceptions\ErrorActionNotDefined();
        }

        if (!class_exists($class)) {
            throw new \Topi\Router\Exceptions\ErrorActionNotDefined();
        }

        return (new $class)->action($request, array(
            'error' => $error
        ));
    }
}
