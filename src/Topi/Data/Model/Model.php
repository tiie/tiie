<?php
namespace Topi\Data\Model;

use Topi\Data\Model\Record;
use Topi\Data\Model\Records;
use Topi\Data\Model\RecordInterface;
use Topi\Data\Model\ModelInterface;

abstract class Model implements ModelInterface
{
    protected $id = 'id';
    protected $pagingParameters = array(
        'page' => 'page',
        'pageSize' => 'pageSize',
    );

    private $records = array();


    public function find(array $params = array()) : Records
    {
        return new Records($this, $this->fetch($params, 1), $params, $this->id);
    }

    public function count(array $params = array()) : string
    {
        unset($params[$this->pagingParameters['page']]);
        unset($params[$this->pagingParameters['pageSize']]);

        return $this->find($params)->count();
    }

    public function createRecord(array $data = array()) : RecordInterface
    {
        return new Record($this, $data, $this->id);
    }

    /**
     * Return list of records from model.
     */
    public function records(array $ids = array(), array $params = array()) : Records
    {
        $items = array();

        foreach ($this->fetchByIds($ids, $params) as $row) {
            $items[] = array(
                'id' => $row[$this->id],
                'record' => $this->createRecord($row),
            );
        }

        return new Records($this, $items, $params, $this->id);
    }

    /**
     * Return record with given id.
     */
    public function record(string $id, array $params = array()) : ?RecordInterface
    {
        $row = $this->fetchById($id, $params);

        if (is_null($row)) {
            return null;
        }

        return $this->createRecord($row);
    }

    public function fetchById(string $id, array $params = array()) : ?array
    {
        $params[$this->id] = $id;

        return empty($rows = $this->fetch($params)) ? null : $rows[0];
    }

    public function fetchByIds(array $ids, array $params = array()) : ?array
    {
        $params[$this->id] = $ids;

        return $this->fetch($params);
    }

    public function run(RecordInterface $record, string $command, array $params = array()) : ModelInterface
    {
        switch ($command) {
        case 'save':
            return $this->save($record, $this, $params);
        case 'remove':
            return $this->remove($record, $this, $params);
        }

        return $this;
    }

    public function validate(RecordInterface $record, string $process, array $params = array()) : ?array
    {
        return null;
    }

    public function save(RecordInterface $record, array $params = array()) : ModelInterface
    {
        throw new \Exception("Save is not implemented");
    }

    public function create(RecordInterface $record, array $params = array()) : string
    {
        throw new \Exception("Create is not implemented");
    }

    public function remove(RecordInterface $record, array $params = array())
    {
        throw new \Exception("Remove is not implemented");
    }
}
