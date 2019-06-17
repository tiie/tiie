<?php
namespace Tiie\Data;

/**
 * @package Tiie\Data
 */
class Tree
{
    /**
     * @var array
     */
    private $nodes;

    /**
     * @var array
     */
    private $params;

    /**
     * Tree constructor.
     * j
     * @param array $nodes
     * @param array $params
     */
    function __construct(array $nodes = array(), array $params = array())
    {
        $this->nodes = $nodes;

        $this->params = array(
            'keyId' => empty($params['keyId']) ? 'id' : $params['keyId'],
            'keyParentId' => empty($params['keyParentId']) ? 'parentId' : $params['keyParentId'],
            'rootId' => array_key_exists("rootId", $params) ? $params["rootId"] : null,
        );
    }

    /**
     * Returns leafs at tree.
     *
     * @param array $params
     *
     * @return array
     */
    public function getLeafs() : array
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

    /**
     * Returns path to given node.
     *
     * @param string $to
     * @param array $params
     *
     * @return array|null
     */
    public function getPath(string $to) : ?array
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

            if ($pointer[$keyParentId] == $this->params["rootId"]) {
                break;
            }

            if (isset($index[$pointer[$keyParentId]])) {
                $pointer = $index[$pointer[$keyParentId]];
            } else {
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

    /**
     * Find node by id.
     *
     * @param string $id
     *
     * @return array|null
     */
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
