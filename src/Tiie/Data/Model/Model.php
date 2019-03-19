<?php
namespace Tiie\Data\Model;

use Tiie\Data\Model\Record;
use Tiie\Data\Model\Records;
use Tiie\Data\Model\RecordInterface;
use Tiie\Data\Model\ModelInterface;
use Tiie\Data\Model\CreatorInterface;
use Tiie\Data\Model\Creator;
use Tiie\Data\Model\Projection;
use Tiie\Data\Model\Relational\SelectableInterface;
use Tiie\Data\Model\Pagination;

abstract class Model implements ModelInterface
{
    protected $id = 'id';
    protected $pagingParameters = array(
        'page' => 'page',
        'pageSize' => 'pageSize',
    );

    private $records = array();

    public function find(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = null) : Records
    {
        $records = array();

        foreach ($this->fetch($params, $fields, $sort, $size, $page) as $item) {
            $records[] = $this->createRecord($item);
        }

        return new Records($this, $records, $this->id);
    }

    public function pagination(array $params = array()) : Pagination
    {
        return new Pagination($this, $params);
    }

    public function counter(array $params = array(), int $size = null, int $page = 0) : array
    {
        $counter = array();

        $number = $this->count($params);
        $counter['total'] = $number;

        if (!is_null($size)) {
            $counter['size'] = $size;
            $counter['page'] = $page;
            $counter['offset'] = $page * $size;
            $counter['pages'] = ceil($number / $size);
        }

        return $counter;
    }

    public function projection() : Projection
    {
        return new Projection($this);
    }

    public function generator(array $params = array()) : iterable
    {
        $count = $this->count($params);
        $limit = 5;
        $offset = 0;
        $packages = array();

        for($i=0; $i < $count; $i++) {
            yield $this->createRecord($this->fetch($params, 1, $i));
        }
    }

    public function count(array $params = array()) : string
    {
        if ($this instanceof SelectableInterface) {
            return $this->select($params)->count();
        } else {
            return $this->find($params)->count();
        }
    }

    public function createRecord(array $data = array()) : RecordInterface
    {
        return new Record($this, $data, $this->id);
    }

    /**
     * Return list of records from model.
     */
    public function records(array $ids, array $params = array(), array $fields = array(), array $sort = array()) : Records
    {
        $items = array();

        foreach ($this->fetchByIds($ids, $params, $fields) as $row) {
            $items[] = array(
                'id' => $row[$this->id],
                'record' => $this->createRecord($row),
            );
        }

        return new Records($this, $items, $this->id);
    }

    /**
     * Return record with given id.
     */
    public function record(string $id, array $params = array(), array $fields = array()) : ?RecordInterface
    {
        $row = $this->fetchById($id, $params, $fields);

        if (is_null($row)) {
            return null;
        }

        return $this->createRecord($row);
    }

    public function fetchById(string $id, array $params = array(), array $fields = array()) : ?array
    {
        $params[$this->id] = $id;

        return empty($rows = $this->fetch($params, $fields)) ? null : $rows[0];
    }

    public function fetchByIds(array $ids, array $params = array(), array $fields = array(), array $sort = array()) : ?array
    {
        $params[$this->id] = $ids;

        return $this->fetch($params, $fields, $sort);
    }

    public function run(RecordInterface $record, string $command, array $params = array()) : ?string
    {
        switch ($command) {
        case self::COMMAND_SAVE:
            return $this->save($record, $params);
        case self::COMMAND_REMOVE:
            return $this->remove($record, $params);
        case self::COMMAND_CREATE:
            return $this->create($record, $params);
        }

        return $this;
    }

    public function validate(RecordInterface $record, string $process, array $params = array()) : ?array
    {
        return null;
    }

    public function creator() : CreatorInterface
    {
        return new Creator($this);
    }

    public function save(RecordInterface $record, array $params = array()) : ?string
    {
        throw new \Exception("Save is not implemented");
    }

    public function create(RecordInterface $record, array $params = array()) : ?string
    {
        throw new \Exception("Create is not implemented");
    }

    public function remove(RecordInterface $record, array $params = array())
    {
        throw new \Exception("Remove is not implemented");
    }
}
