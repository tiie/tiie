<?php
namespace Topi\Data\Model;

use Topi\Data\Model\Records;
use Topi\Data\Model\RecordInterface;
use Topi\Data\Model\ModelInterface;

class Records
{
    private $model;
    private $items = array(
        // array(
        //     'id' => 10,
        //     'record' => null,
        // )
    );

    private $params = array();
    private $fieldId;

    function __construct(ModelInterface $model, array $items = array(), array $params = array(), string $fieldId = 'id')
    {
        $this->model = $model;
        $this->items = $items;
        $this->params = $params;
        $this->fieldId = $fieldId;
    }

    public function count()
    {
        return count($this->items);
    }

    public function get(string $id) : ?RecordInterface
    {
        $foundKey = null;

        foreach ($this->items as $key => $item) {
            if ($item['id'] == $id) {
                $foundKey = $key;
            }
        }

        if (!is_null($foundKey)) {
            if (!isset($this->items[$foundKey]['record'])) {
                $this->items[$foundKey]['record'] = $this->model->record($this->items[$foundKey]['id'], $this->params);
            }
        } else {
            return null;
        }

        return $this->items[$foundKey]['record'];
    }

    /**
     * Return list of records at collection.
     *
     * @return array
     */
    public function records() : array
    {
        $this->load();

        $records = array();

        foreach ($this->items as $item) {
            $records[] = $item['record'];
        }

        return $records;
    }

    public function column(string $name)
    {
        $column = array();

        foreach ($this->records() as $record) {
            $value = $record->get($name);

            if (!is_null($value = $record->get($name))) {
                $column[] = $value;
            }
        }

        return $column;
    }

    private function load()
    {
        $toload = array();

        foreach ($this->items as $item) {
            if (!isset($item['record'])) {
                // Record does not exist. I save id to load record.
                $toload[] = $item[$this->fieldId];
            }
        }

        if (!empty($toload)) {
            $records = $this->model->records($toload, $this->params);

            foreach ($records->records($toload) as $record) {
                foreach ($this->items as $key => $item) {
                    if ($item[$this->fieldId] == $record->id()) {
                        $this->items[$key]['record'] = $record;
                    }
                }
            }
        }
    }

    public function toArray(array $params = array()) : array
    {
        $array = array();

        foreach ($this->records() as $record) {
            $array[] = $record->toArray($params);
        }

        return $array;
    }
}
