<?php
namespace Elusim\Documentation;

class Analyzer
{
    private $actions;

    function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function analyze()
    {
        $data = array();

        foreach ($this->actions as $action) {
            if ($action['type'] == 'rest') {
                $files = \Elusim\func\scandirr($action['dir']);

                foreach ($files as $file) {
                    if (\Elusim\func\fileExtension($file) != 'php') {
                        continue;
                    }

                    // if ($file != '../src/Actions/Offers/Form.php') {
                    //     continue;
                    // }

                    $information = $this->tokenizer($file);

                    if (!is_null($information['class'])) {
                        $action['url'] = strtolower($action['prefix'].substr($file, strlen($action['dir'])));
                        $action['url'] = substr($action['url'], 0, strlen($action['url']) - 4);

                        $class = "\\".$information['namespace']."\\".$information['class'];

                        $action['metadata'] = $class::metadata();

                        $data[] = $action;
                    }
                }
            }
        }

        return $data;
    }

    private function tokenizer($file)
    {
        $namespace = "";
        $class = "";

        $tokens = token_get_all(file_get_contents($file));
        $state = null;

        foreach ($tokens as $key => $token) {
            if (is_array($token)) {
                if ($token[0] == 388 && strtolower($token[1]) == 'namespace') {
                    $state = 'namespace';

                    continue;
                }

                if ($token[0] == 361 && strtolower($token[1]) == 'class') {
                    $state = 'class';

                    continue;
                }

                if ($token[1] == '{' && $state == 'class') {
                    $state = null;
                    break;
                }

                if ($token[1] == 'extends' && $state == 'class') {
                    $state = null;
                    break;
                }

                if ($state == 'namespace') {
                    $namespace .= $token['1'];
                    continue;
                }

                if ($state == 'class') {
                    $class .= $token['1'];
                }

            }else{
                if ($token == ';' && $state == 'namespace') {
                    $state = null;
                }
            }
        }

        if (empty($namespace)) {
            $namespace = null;
        }else{
            $namespace = trim($namespace);
        }

        if (empty($class)) {
            $class = null;
        }else{
            $class = trim($class);
        }

        return array(
            'namespace' => $namespace,
            'class' => $class,
        );
    }
}
