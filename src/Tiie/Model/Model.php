<?php
namespace Tiie\Model;

use Tiie\Model\Record;
use Tiie\Model\Records;
use Tiie\Model\RecordInterface;
use Tiie\Model\ModelInterface;
use Tiie\Model\CreatorInterface;
use Tiie\Model\Creator;
use Tiie\Model\Projection;
use Tiie\Model\Relational\SelectableInterface;
use Tiie\Model\Pagination;

use Tiie\Commands\CommandInterface;
use Tiie\Commands\Result\ResultInterface;
use Tiie\Commands\Exceptions\ValidationFailed;

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

    public function getPagination(array $params = array()) : Pagination
    {
        return new Pagination($this, $params);
    }

    public function getCounter(array $params = array(), int $size = null, int $page = 0) : array
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

    public function getProjection() : Projection
    {
        return new Projection($this);
    }

    public function getGenerator(array $params = array()) : iterable
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

    public function factory(string $type, array $data = array())
    {
        // $this->persidence();

        // $offer->save();
        // $offer->remove();
        // $offer->duplicate();

        // $offer->run(COMMAND_SAVE);
        // $offer->run(COMMAND_SAVE);
        // $offer->run(COMMAND_SAVE);
        // $offer->run(COMMAND_SAVE);
        // $offer->run(COMMAND_SAVE);

        if ($type == ModelInterface::FACTORY_TYPE_RECORD) {
            return new Record($this, $data, $this->id);
        } else {
            return null;
        }
    }

    public function createRecord(array $data = array()) : RecordInterface
    {
        return new Record($this, $data, $this->id);
    }

    /**
     * Return list of records from model.
     */
    public function getRecords(array $ids, array $params = array(), array $fields = array(), array $sort = array()) : Records
    {
        $items = array();

        foreach ($this->fetchByIds($ids, $params, $fields) as $row) {
            // $items[] = array(
            //     'id' => $row[$this->id],
            //     'record' => $this->createRecord($row),
            // );

            $items[] = $this->createRecord($row);
        }

        return new Records($this, $items, $this->id);
    }

    /**
     * Return record with given id.
     */
    public function getRecord(string $id, array $params = array(), array $fields = array()) : ?RecordInterface
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

    public function run(CommandInterface $command, array $params = array()) : ?ResultInterface
    {
        trigger_error(sprintf("There is no implementation of %s command.", get_class($command)), E_USER_WARNING);

        return null;
    }

    public function validate(CommandInterface $command, array $params = array()) : ?array
    {
        return null;
    }

    protected function validateThrow(CommandInterface $command, array $params = array())
    {
        if (!is_null($errors = $this->validate($command, $params))) {
            throw new ValidationFailed($errors);
        }
    }

    public function getCreator() : CreatorInterface
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
