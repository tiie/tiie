<?php
namespace Tiie\Router;

use Tiie\Http\Request;
use Tiie\Response\ResponseInterface;
use Tiie\Router\Exceptions\RouteNotFound;
use Tiie\Router\Exceptions\ActionNotFound;
use Tiie\Router\Exceptions\MethodNotFound;

/**
 * Class Router
 *
 * @package Tiie\Router
 */
class Router
{
    /**
     * @var string
     */
    const OBJECT_ROUTE = 'route';

    /**
     * @var string
     */
    const OBJECT_ACTION = 'action';

    /**
     * @var array
     */
    private $routes;

    /**
     * @var array|null
     */
    private $route;

    /**
     * @var array|null
     */
    private $group;

    /**
     * @var array
     */
    private $params;

    /**
     * @var Request
     */
    private $request;

    /**
     * Router constructor.
     *
     * @param array $params
     */
    function __construct(array $params = array())
    {
        if (empty($params['routes'])) {
            $this->routes = array();
        } else {
            $this->routes = $params["routes"];
        }
    }

    /**
     * @return Group|null
     */
    public function group() : ?Group
    {
        return is_null($this->group) ? null : new Group($this->group);
    }

    /**
     * @return Route|null
     */
    public function getRoute() : ?Route
    {
        return is_null($this->route) ? null : new Route($this->route);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getParam(string $name)
    {
        return array_key_exists($name, $this->params) ? $this->params[$name] : null;
    }

    /**
     * The process can be started in two ways. The first is to start the data
     * transfer, the second is to run the prepre 'before launching.
     *
     * @param Request|null $request
     *
     * @return null|ResponseInterface
     * @throws ActionNotFound
     * @throws MethodNotFound
     * @throws RouteNotFound
     */
    public function run() : ?ResponseInterface
    {
        if (is_null($this->group) || is_null($this->route)) {
            throw new RouteNotFound("Route not found for {$this->request->__toString()}");
        }

        if (empty($this->route["action"]["class"]) && !class_exists("\\{$this->route["action"]["class"]}")) {
            throw new ActionNotFound("Action not found for {$this->request->__toString()}");
        }

        if (!class_exists("\\{$this->route["action"]["class"]}")) {
            throw new ActionNotFound("Action not found for {$this->request->__toString()}");
        }

        if (empty($this->route["action"]["method"]) || !in_array($this->route['action']['method'], get_class_methods($this->route['action']['class']))) {
            throw new MethodNotFound("Method not found for {$this->request->__toString()}");
        }

        $class = $this->route['action']['class'];
        $method = $this->route['action']['method'];

        $this->request->setParams($this->params);

        return (new $class())->$method($this->request);
    }

    /**
     * Prepare router to run request.
     *
     * @param Request $request
     */
    public function prepare(Request $request) : void
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

        foreach ($this->routes as $groupId => $group) {
            if (is_string($groupId)) {
                $group["name"] = $groupId;
            }

            $group["params"] = array();

            if (!empty($group["domain"])) {
                $m = $this->matchString($request->getDomain(), $group['domain']);

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
                $m = $this->matchString($request->getUrn(), "{$group['prefix']}", array("begin" => 1));

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
            if (is_string($routeId)) {
                $route["id"] = $routeId;
            }

            // method
            if (!empty($route['method'])) {
                if ($route['method'] != strtolower($request->getMethod())) {
                    continue;
                }
            }

            // urn
            $urn = $route["urn"];

            if (!empty($match["group"]["prefix"])) {
                $urn = "{$match["group"]["prefix"]}{$urn}";
            }

            $m = $this->matchString($request->getUrn(), $urn);

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

        // find places holders
        preg_match_all('/({(.+?)})/m', $regex, $matches, PREG_SET_ORDER, 0);

        $regex = str_replace("/", "\/", $regex);
        $regex = str_replace(".", "\.", $regex);

        foreach ($matches as $match) {
            $name = $match[2];

            if ($name == "*") {
                $paramregex = ".*";
            } else {
                $paramregex = "([^\/]+?)";
            }

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

            if ($name != "*") {
                $paramsNames[] = $name;
            }

            $regex = str_replace($match[0], $paramregex, $regex);
        }

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
