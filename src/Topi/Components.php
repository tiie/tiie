<?php
namespace Topi;

class Components
{
    private $config;
    private $inited = array();
    private $initers = array();

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

    public function get($name, $params = array())
    {
        if ($name[0] == '@') {
            if (!isset($this->inited[$name])) {
                $initer = $this->findIniter($name);

                if (is_null($initer)) {
                    throw new \Exception(sprintf('Component %s is not defined.', $name));
                }

                $this->inited[$name] = $initer($this, $params);
            }

            return $this->inited[$name];
        }else{
            if (!isset($this->initers[$name])) {
                $initer = $this->findIniter($name);

                if (is_null($initer)) {
                    throw new \Exception(sprintf('Component %s is not defined.', $name));
                }

                $this->initers[$name] = $initer;
            }

            $initer = $this->initers[$name];

            return $initer($this, $params);
        }
    }

    public function set($name, $component)
    {
        $this->inited[$name] = $component;

        return $this;
    }

    public function defined($name)
    {
        if ($name[0] == '@') {
            if (isset($this->inited[$name])) {
                return 1;
            }else{
                return is_null($this->findIniter($name)) ? 0 : 1;
            }
        }else{
            return is_null($this->findIniter($name)) ? 0 : 1;
        }
    }

    private function findIniter($name)
    {
        // $dirs = array_reverse($this->config['dirs']);
        $dirs = $this->config['dirs'];

        foreach ($dirs as $dir) {
            $path = sprintf('%s/%s.php', $dir, $name);

            if (file_exists($path)) {
                return include($path);
            }
        }

        return null;
    }
}
