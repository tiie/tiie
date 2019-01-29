<?php
namespace Tiie\Data\Tree;

class Tree
{
    private $data;
    private $tree;
    private $nodes = array();
    private $keyValue;
    private $keyParentValue;
    private $rootId;

    function __construct(array $data, array $params = array())
    {
        $this->keyValue = empty($params['keyValue']) ? 'id' : $params['keyValue'];
        $this->keyParentValue = empty($params['keyParentValue']) ? 'parentId' : $params['keyParentValue'];
        $this->rootId = empty($params['rootId']) ? null : $params['rootId'];

        $this->data = $data;

        $this->load($data);
    }

    private function load(array $data)
    {
        $this->tree = array();

        while(count($data) > 0) {
            $v = array_shift($data);
            $keyValue = $v[$this->keyValue];

            if (!isset($this->tree[$keyValue])) {
                $node = (object) $v;
                $node->_id = $keyValue;
                $node->_parentId = $v[$this->keyParentValue];
                $node->_childs = array();

                $this->tree[$keyValue] = $node;
            }

            if ($keyValue != $this->rootId) {
                if (isset($this->tree[$v[$this->keyParentValue]])) {
                    $this->tree[$v[$this->keyParentValue]]->_childs[] = $this->tree[$keyValue];
                }
            }
        }

        // die('a');
        // die(print_r($this->tree));
    }

    /**
     * Tworzy ścieżkę do wskazanego noda.
     */
    public function path($end, $begin = null, string $format = 'path')
    {
        $path = array();

        if (is_null($begin)) {
            $begin = $this->rootId;
        }

        if (!isset($this->tree[$end])) {
            return null;
        }

        $pointer = $this->tree[$end];

        while(1) {
            if (is_null($begin)) {
                if ($pointer->_parentId == $begin) {
                    break;
                }
            }else{
                if ($pointer->_id == $begin) {
                    break;
                }
            }

            if (!isset($this->tree[$pointer->_parentId])) {
                return null;
            }else{
                $path[] = $pointer;
                $pointer = $this->tree[$pointer->_parentId];
            }
        }

        $path[] = $pointer;

        $path = json_decode(json_encode($path), 1);

        foreach ($path as $key => $element) {
            unset($path[$key]['_id']);
            unset($path[$key]['_childs']);
            unset($path[$key]['_parentId']);
        }

        return array_reverse($path);
    }

    public function tree($rootId, $format = 'tree')
    {
        if (!isset($this->tree[$rootId])) {
            return null;
        }

        switch ($format) {
        case 'tree':
           return json_decode(json_encode($this->tree[$rootId]), 1);
        case 'flat':
            // Wrzucan na stos pierwszy element.
            $pointers = array($this->tree[$rootId]);

            // Lista wszystkich elementów.
            $elements = array();
            $index = array();

            do {
                $pointer = array_pop($pointers);

                if (!isset($index[$pointer->_id])) {
                    $elements[] = $pointer;
                    $index[$pointer->_id] = 1;
                }

                if (count($pointer->_childs) > 0) {
                    $pointers[] = $pointer;
                    $pointers[] = array_shift($pointer->_childs);

                    continue;
                }

            } while(count($pointers) > 0);

            return json_decode(json_encode($elements), 1);
        default:
            break;
        }
    }
}

