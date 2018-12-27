<?php
namespace Elusim\Data;

class Tree
{
    private $nodes;
    private $params;

    function __construct(array $nodes = array(), array $params = array())
    {
        $this->nodes = $nodes;

        $this->params = array(
            'keyId' => empty($params['keyId']) ? 'id' : $params['keyId'],
            'keyParentId' => empty($params['keyParentId']) ? 'parentId' : $params['keyParentId'],
        );
    }

    public function leafs(array $params = array()) : array
    {
        $leafs = array();
        $keyId = $this->params['keyId'];
        $keyParentId = $this->params['keyParentId'];

        foreach ($this->nodes as $node) {
            $found = 0;

            foreach ($this->nodes as $subnode) {
                if ($subnode[$keyId] == $node[$keyId]) {
                    continue;
                }

                if ($subnode[$keyParentId] == $node[$keyId]) {
                    $found = 1;
                    break;
                }
            }

            if (!$found) {
                $leafs[] = $node;
            }
        }

        return $leafs;
    }

    public function path(string $to, array $params = array()) : ?array
    {
        $toNode = $this->findById($to);

        if (is_null($toNode)) {
            return null;
        }

        $keyId = $this->params['keyId'];
        $keyParentId = $this->params['keyParentId'];
        $path = array();
        $pointer = $toNode;
        $index = array();

        while(!is_null($pointer)) {
            $path[] = $pointer;

            if (is_null($pointer[$keyParentId])) {
                break;
            }

            if (isset($index[$pointer[$keyParentId]])) {
                $pointer = $index[$pointer[$keyParentId]];
            } else {
                // $id = $pointer[$keyId];
                // $parentId = $pointer[$keyParentId];
                // $pointer = null;
                $found = null;

                foreach ($this->nodes as $node) {
                    if (!isset($index[$node[$keyId]])) {
                        $index[$node[$keyId]] = $node;
                    }

                    if ($node[$keyId] == $pointer[$keyParentId]) {
                        $found = $node;

                        break;
                    }
                }

                if (is_null($found)) {
                    trigger_error("The parent '{$pointer[$keyParentId]}' for the record '{$pointer[$keyId]}' could not be found. Probably the tree is not consistent.", E_USER_NOTICE);

                    return $path;
                } else {
                    $pointer = $found;
                }
            }
        }

        return array_reverse($path);
    }

    public function findById(string $id) : ?array
    {
        $found = null;
        $keyId = $this->params['keyId'];

        foreach ($this->nodes as $node) {
            if ($node[$keyId] == $id) {
                $found = $node;

                break;
            }
        }

        return $found;
    }
}
