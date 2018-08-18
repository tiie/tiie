<?php
namespace Topi;

use Topi\Components\Scope;

class Components
{
    private $config;
    private $services = array();
    private $initers = array();
    private $scopes = array();

    function __construct(array $config = array())
    {
        if (isset($config['dirs'])) {
            // Dorzucam podstawową ścieżke
            $config['dirs'][] = __DIR__.'/Components';
        }else{
            // Nie podano ścieżki więc ustawiam jedną ścieżkę z defaultową
            // ścieżko dla aplikacji
            $config['dirs'] =  array(
                '../src/App/Components,',
                __DIR__.'/Components',
            );
        }

        $this->config = $config;
    }

    public function get(string $name, array $params = array(), string $scope = null)
    {
        if ($name[0] == '@' && array_key_exists($name, $this->services)) {
            return $this->services[$name];
        }

        if (!is_null($scope)) {
            if (array_key_exists($name, $this->services)) {
                // I check if component is at services.
                $this->scopes[$scope][$name] = array(
                    'initer' => null,
                    'object' => $this->services[$name],
                );
            }

            // Scope is defined. I check if there is component at scope. If not
            // then I init component, otherwise I return inited component.
            if (!array_key_exists($name, $this->scopes[$scope])) {
                $this->scopes[$scope][$name] = $this->initComponent($name, $params);
            }

            return $this->scopes[$scope][$name]['object'];
        }

        // create new scope
        // model.user
        $scope = uniqid();
        $this->scopes[$scope] = array();
        $component = $this->scopes[$scope][$name] = $this->initComponent($name, $params);

        if (array_key_exists('after', $component['initer'])) {
            if (is_callable($component['initer']['after'])) {
                call_user_func_array($component['initer']['after'], array($component['object'], new Scope($this, $scope), $params));
            }
        }

        foreach ($this->scopes[$scope] as $componentname => $other) {
            if ($componentname == $name) {
                continue;
            }

            if (!is_null($other['initer'])) {
                if (array_key_exists('after', $other['initer'])) {
                    if (is_callable($other['initer']['after'])) {
                        call_user_func_array($other['initer']['after'], array($other['object'], new Scope($this, $scope), $params));
                    }
                }
            }

            if ($componentname[0] == '@') {
                if (!array_key_exists($componentname, $this->services)) {
                    $this->services[$componentname] = $other['object'];
                }
            }
        }

        unset($this->scopes[$scope]);

        return $component['object'];
    }

    private function initComponent(string $name, array $params = array())
    {
        $initer = $this->initer($name);

        if (!is_callable($initer['init'])) {
            return array(
                'initer' => $initer,
                'object' => $initer['init'],
            );
        }

        return array(
            'initer' => $initer,
            'object' => call_user_func_array($initer['init'], array($this, $params)),
        );
    }

    public function set($name, $component)
    {
        if ($name[0] == '@') {
            $this->services[$name] = $component;

            return $this;
        }

        throw new \Exception("{$name} only services can be set.");
    }

    public function defined($name)
    {
        return is_null($this->initer($name)) ? 0 : 1;
    }

    private function initer($name)
    {
        foreach ($this->config['dirs'] as $dir) {
            $path = sprintf('%s/%s.php', $dir, $name);

            if (file_exists($path)) {
                $initer = include($path);

                if (is_callable($initer)) {
                    $initer = array(
                        'init' => $initer,
                    );
                }

                return $initer;
            }
        }

        throw new \Exception("Component {$name} is not defined.");
    }
}
