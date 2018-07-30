<?php
namespace Topi\Data\Tree\Path\Creators;

class Adapter
{
    private $leafId;
    private $table;
    private $db;
    private $params;

    function __construct($leafId, $table, $db, $params = array())
    {
        $this->leafId = $leafId;
        $this->table = $table;
        $this->db = $db;

        $this->params = array_replace(array(
            'keyId' => 'id',
            'keyParent' => 'parentId',
            'rootId' => null
        ), $params);
    }

    public function fetch()
    {
        $tree = array();
        $keyId = $this->params['keyId'];
        $keyParent = $this->params['keyParent'];
        $table = $this->table;

        $leaf = new \Topi\Data\Adapters\Commands\SQL\Select();
        $leaf
            ->from($table)
            ->eq($keyId, $this->leafId);
        ;

        $leaf = $this->db->fetch($leaf, 'row');

        if (is_null($leaf)) {
            throw new \Exception("Not leaf with id {$this->leafId}");
        }

        $tree[] = $leaf;

        $stmp = (new \Topi\Data\Adapters\Commands\SQL\Select())
            ->from($table)
            ->expr("{$keyId} = :id")
            ->build()
        ;

        // fetch parents
        while(true){
            if ($leaf[$keyParent] == $this->params['rootId']) {
                break;
            }

            $parent = $this->db->fetch($stmp, 'row', array(
                'id' => $leaf[$keyParent]
            ));

            if (is_null($parent)) {
                throw new \Exception("Not parent for tree");
            }

            $tree[] = $parent;

            $leaf = $parent;
        }

        return array_reverse($tree);
    }
}
