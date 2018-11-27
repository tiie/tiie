<?php
namespace Elusim\Router;

use Elusim\Http\Request;

class Router
{
    const OBJECT_ROUTE = 'route';
    const OBJECT_ACTION = 'action';

    /**
     * @var \Elusim\Config Reference to params object.
     */
    private $params;

    /**
     * List of all routes.
     */
    private $routes;

    function __construct(array $params = array())
    {
        // routes
        if (empty($params['routes'])) {
            $this->routes = array();
        } else {
            foreach ($params['routes'] as $routeId => $route) {
                $route['id'] = $routeId;

                if (!empty($route['group'])) {
                    if (!empty($params['groups'][$route['group']])) {
                        $route = array_merge($params['groups'][$route['group']], $route);
                    }
                }

                if (empty($route['method'])) {
                    $route['method'] = 'get';
                } else {
                    $route['method'] = strtolower($route['method']);
                }

                if (empty($route['action'])) {
                    throw new \Exception("Route does not have action defined.");
                } else {
                    if (empty($route['action']['class'])) {
                        throw new \Exception("Route does not have action.class defined.");
                    }

                    if (empty($route['action']['method'])) {
                        $route['action']['method'] = $route['method'];
                    }
                }

                if (empty($route['urn'])) {
                    throw new \Exception("Route does not have urn defined.");
                }

                $this->routes[$routeId] = $route;
            }
        }
    }

    public function run(Request $request) : \Elusim\Response\ResponseInterface
    {
        $route = $this->match($request);

        if (is_null($route)) {
            throw new \Elusim\Router\Exceptions\RouteNotFound("Route not found for {$request->__toString()}");
        }

        if (!class_exists("\\".$route['action']['class'])) {
            throw new \Elusim\Router\Exceptions\ActionNotFound("Action not found for {$request->__toString()}");
        }

        if (!in_array($route['action']['method'], get_class_methods($route['action']['class']))) {
            throw new \Elusim\Router\Exceptions\MethodNotFound("Method not found for {$request->__toString()}");
        }

        $class = $route['action']['class'];
        $method = $route['action']['method'];

        $request->params($route['params']);

        return (new $class())->$method($request);
    }

    /**
     * Find route which match to given request.
     *
     * @return null|Request Null if return when route is not found.
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $routeId => $route) {
            $params = array();

            // method
            if (!empty($route['method'])) {
                if ($route['method'] != strtolower($request->method())) {
                    continue;
                }
            }

            // domain
            if (!empty($route['domain'])) {
                $matched = $this->matchString($request->domain(), $route['domain']);

                if (is_null($matched)) {
                    continue;
                }

                $params = array_merge($params, $matched['params']);
            }

            // urn
            $matched = $this->matchString($request->urn(), $route['urn']);

            if (empty($matched)) {
                continue;
            }

            $params = array_merge($params, $matched['params']);

            $route['params'] = $params;

            return $route;
        }

        return null;
    }

    /**
     * Check if given string match to regex. If match then return data. If not
     * then return null.
     *
     * @return array|null
     */
    private function matchString(string $string, string $regex) : ?array
    {
        $paramsNames = array();
        $paramsvalues = array();

        // find places holders
        preg_match_all('/({(.+?)})/m', $regex, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $name = $match[2];
            $paramregex = "([^/]+?)";

            if (strpos($name, ":") != false) {
                $exploded = explode(":", $name);
                $name = $exploded[1];

                switch ($exploded[0]) {
                case 'i':
                    $paramregex = '([0-9]++)';
                    break;
                case 'a':
                    $paramregex = '([0-9A-Za-z]++)';
                    break;
                case 'h':
                    $paramregex = '([0-9A-Fa-f]++)';
                    break;
                case '*':
                    $paramregex = '(.+?)';
                    break;
                case '**':
                    $paramregex = '(.++)';
                    break;
                default:
                    $paramregex = $exploded[0];
                    break;
                // case '':
                //     $paramregex = '[^/\.]++';
                //     break;
                }
            }

            // append paramsNames name
            $paramsNames[] = $name;

            $regex = str_replace($match[0], $paramregex, $regex);
        }

        $regex = str_replace("/", "\/", $regex);
        $regex = str_replace(".", "\.", $regex);
        $regex = "/^{$regex}$/m";

        // check if regex matched
        preg_match_all($regex, $string, $matches, PREG_SET_ORDER, 0);

        if (empty($matches)) {
            return null;
        }

        $params = array();

        foreach ($paramsNames as $key => $name) {
            $params[$name] = $matches[0][$key+1];
        }

        return array(
            "params" => $params
        );
    }
}
