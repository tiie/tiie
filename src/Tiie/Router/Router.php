<?php
namespace Tiie\Router;

use Tiie\Http\Request;
use Tiie\Response\ResponseInterface;
use Tiie\Router\Group;
use Tiie\Router\Route;
use Tiie\Router\Exceptions\RouteNotFound;
use Tiie\Router\Exceptions\ActionNotFound;
use Tiie\Router\Exceptions\MethodNotFound;

class Router
{
    const OBJECT_ROUTE = 'route';
    const OBJECT_ACTION = 'action';

    /**
     * List of all routes.
     */
    private $routes;

    private $route;
    private $group;
    private $params;
    private $request;

    function __construct(array $params = array())
    {
        // routes
        if (empty($params['routes'])) {
            $this->routes = array();
        } else {
            $this->routes = $params["routes"];
        }
    }

    public function group()
    {
        return is_null($this->group) ? null : new Group($this->group);
    }

    public function route()
    {
        return is_null($this->route) ? null : new Route($this->route);
    }

    public function params()
    {
        return $this->params;
    }

    public function param(string $name)
    {
        return array_key_exists($name, $this->params) ? $this->params[$name] : null;
    }

    /**
     * The process can be started in two ways. The first is to start the data
     * transfer, the second is to run the prepre 'before launching.
     *
     * @param \Tiie\Http\Request $request
     * @return null|Tiie\Response\ResponseInterface
     *
     * @throws \Tiie\Router\Exceptions\RouteNotFound
     * @throws \Tiie\Router\Exceptions\ActionNotFound
     * @throws \Tiie\Router\Exceptions\MethodNotFound
     */
    public function run(Request $request = null) : ?ResponseInterface
    {
        if (!is_null($request)) {
            $this->prepare($request);
        }

        if (is_null($this->group) || is_null($this->route)) {
            throw new RouteNotFound("Route not found for {$this->request->__toString()}");
        }

        if (empty($this->route["action"]["class"]) && !class_exists("\\{$this->route["action"]["class"]}")) {
            throw new ActionNotFound("Action not found for {$this->request->__toString()}");
        }

        if (empty($this->route["action"]["method"]) || !in_array($this->route['action']['method'], get_class_methods($this->route['action']['class']))) {
            throw new MethodNotFound("Method not found for {$this->request->__toString()}");
        }

        $class = $this->route['action']['class'];
        $method = $this->route['action']['method'];

        $this->request->params($this->params);

        return (new $class())->$method($this->request);
    }

    public function prepare(Request $request) : Router
    {
        $this->request = $request;

        $match = $this->match($request);

        $this->group = $match["group"];
        $this->route = $match["route"];

        if (!is_null($this->group)) {
            $this->params = $this->group["params"];
        }

        if (!is_null($this->route)) {
            $this->params = array_merge($this->group["params"], $this->route["params"]);
        }

        return $this;
    }

    /**
     * Find route which match to given request.
     *
     * @return null|Request Null if return when route is not found.
     */
    public function match(Request $request) : ?array
    {
        $match = array(
            "group" => null,
            "route" => null,
        );

        foreach ($this->routes as $name => $group) {
            $group["name"] = $name;
            $group["params"] = array();

            if (!empty($group["domain"])) {
                $m = $this->matchString($request->domain(), $group['domain']);

                if (is_null($m)) {
                    continue;
                }

                // First I copy params.
                $group['params'] = $m['params'];
            }

            // Domain match now I check if prefix match.
            if (empty($group["prefix"])) {
                $match["group"] = $group;

                break;
            } else {
                $m = $this->matchString($request->urn(), "{$group['prefix']}", array("begin" => 1));

                if (is_null($m)) {
                    continue;
                }

                $group['params'] = array_merge($group['params'], $m['params']);
                $match["group"] = $group;

                break;
            }
        }

        if (empty($match["group"])) {
            return $match;
        }

        if (empty($match["group"]["map"])) {
            return $match;
        }

        foreach ($match["group"]["map"] as $routeId => $route) {
            $route["id"] = $routeId;

            // method
            if (!empty($route['method'])) {
                if ($route['method'] != strtolower($request->method())) {
                    continue;
                }
            }

            // urn
            $urn = $route["urn"];

            if (!empty($match["group"]["prefix"])) {
                $urn = "{$match["group"]["prefix"]}{$urn}";
            }

            $m = $this->matchString($request->urn(), $urn);

            if (empty($m)) {
                continue;
            }

            $route["params"] = $m["params"];
            $match["route"] = $route;

            break;
        }

        return $match;
    }

    /**
     * Check if given string match to regex. If match then return data. If not
     * then return null.
     *
     * @return array|null
     */
    private function matchString(string $string, string $regex, array $params = array()) : ?array
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

        if (!empty($params["begin"])) {
            $regex = "/^{$regex}/m";
        } else {
            $regex = "/^{$regex}$/m";
        }

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
