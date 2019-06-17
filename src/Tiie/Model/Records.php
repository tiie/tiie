<?php
namespace Tiie\Model;

use Tiie\Model\Records;
use Tiie\Model\RecordInterface;
use Tiie\Model\ModelInterface;
use Iterator;

class Records implements \Countable, Iterator
{
    private $model;
    private $fieldId;
    private $records = array();

    private $index = array();

    private $pointer = 0;
    private $array = array(
        "firstelement",
        "secondelement",
        "lastelement",
    );

    function __construct(ModelInterface $model, array $records = array(), string $fieldId = 'id')
    {
        $this->records = $records;
        $this->model = $model;
        $this->fieldId = $fieldId;
        $this->pointer = 0;
    }

    public function shuffle()
    {
        shuffle($this->records);

        return $this;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->records[$this->pointer];
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        ++$this->pointer;
    }

    public function valid()
    {
        return isset($this->records[$this->pointer]);
    }

    public function count() : int
    {
        return count($this->records);
    }

    public function get(string $id) : ?RecordInterface
    {
        foreach ($this->records as $record) {
            if ($this->get($this->fieldId) == $id) {
                return $record;
            }
        }

        return null;
    }

    public function __debugInfo()
    {
        return $this->records;
    }

    /**
     * Return list of records at collection.
     *
     * @return array
     */
    public function getRecords() : array
    {
        return $this->records;
    }

    public function column(string $name)
    {
        $column = array();

        foreach ($this->records as $record) {
            $value = $record->get($name);

            if (!is_null($value = $record->get($name))) {
                $column[] = $value;
            }
        }

        return $column;
    }

    public function first() : ?RecordInterface
    {
        if (count($this->records) == 0) {
            return null;
        }

        return $this->records[0];
    }

    public function findByKey(array $key, array $values) : Records
    {
        $indexKey = implode("-", $key);
        $valuesKey = implode("-", $values);

        $records = array();

        if (!array_key_exists($indexKey, $this->index)) {
            $index = array();

            foreach ($this->records as $record) {
                $keyValue = array();

                foreach ($key as $field) {
                    $fieldValue = $record->get($field);

                    if (is_null($fieldValue)) {
                        continue 2;
                    } else {
                        $keyValue[] = $fieldValue;
                    }
                }

                $keyValue = implode("-", $keyValue);

                if (!array_key_exists($keyValue, $index)) {
                    $index[$keyValue] = array($record);
                } else {
                    $index[$keyValue][] = $record;
                }
            }

            $this->index[$indexKey] = $index;
        }

        $records = array();

        if (array_key_exists($valuesKey, $this->index[$indexKey])) {
            $records = $this->index[$indexKey][$valuesKey];
        }

        return new Records($this->model, $records, $this->fieldId);
    }

    public function findRecordByKey(array $key, array $values) : Records
    {
        $records = $this->findByKey($key, $values);

        return $records->empty() ? null : $records->first();
    }

    public function last() : RecordInterface
    {
        if (count($this->records) == 0) {
            return null;
        }

        return $this->records[count($this->records)-1];
    }

    public function empty()
    {
        return empty($this->records);
    }

    public function toArray(array $params = array()) : array
    {
        $array = array();

        foreach ($this->records as $record) {
            $array[] = $record->toArray($params);
        }

        return $array;
    }
}
