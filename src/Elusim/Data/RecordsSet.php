<?php
namespace Elusim\Data;

/**
 *
 */
class RecordsSet implements \Iterator, \Countable
{
    private $data;
    private $position;

    function __construct($data = array())
    {
        $this->data = $data;
        $this->position = 0;
    }

    /**
     * Countable
     */
    public function count()
    {
        return count($this->data);
    }

    public function empty()
    {
        return $this->count() == 0;
    }

    /**
     * Iterator
     */
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    public function join($name, $rules, $data)
    {
        // $this->join(array('name' => 'client'), 'clientId', $data);
        // $this->join(array('name' => array('client', 'phone')), 'clientId', $data);
        // $this->join('phones', 'clientId', $data);
        // $this->join('phones', array('clientId'), $data);
        // $this->join('phones', array(
        //     'clientId' => 'idClient'
        // ), $data);

        if (!is_array($rules)) {
            $rules = array($rules);
        }

        $joinKey = array();
        $key = array();

        foreach ($rules as $i => $value) {
            if (is_numeric($i)) {
                $key[] = $value;
                $joinKey[] = $value;
            }else{
                $key[] = $i;
                $joinKey[] = $value;
            }
        }

        $index = new \Elusim\Data\Index($data, $joinKey, true);

        if (count($key) == 1) {
            $key = $key[0];

            foreach ($this->data as $i => $row) {
                $value = $index->get($row[$key]);

                if (is_null($value)) {
                    $value = array();
                }

                $this->data[$i][$name] = $value;
            }
        }else{
            foreach ($this->data as $key => $row) {
                $keyValues = array();

                foreach ($key as $value) {
                    $keyValues[] = $row[$value];
                }

                $this->data[$i][$name] = $value;
            }
        }

        return $this;
    }

    public function joinOne($name, $rules, $data)
    {
        // $this->join(array('name' => 'client'), 'clientId', $data);
        // $this->join(array('name' => array('client', 'phone')), 'clientId', $data);
        // $this->join('phones', 'clientId', $data);
        // $this->join('phones', array('clientId'), $data);
        // $this->join('phones', array(
        //     'clientId' => 'idClient'
        // ), $data);

        if (!is_array($rules)) {
            $rules = array($rules);
        }

        $joinKey = array();
        $key = array();

        foreach ($rules as $i => $value) {
            if (is_numeric($i)) {
                $key[] = $value;
                $joinKey[] = $value;
            }else{
                $key[] = $i;
                $joinKey[] = $value;
            }
        }

        $index = new \Elusim\Data\Index($data, $joinKey, false);

        if (count($key) == 1) {
            $key = $key[0];

            foreach ($this->data as $i => $row) {
                $value = $index->get($row[$key]);

                if (is_null($value)) {
                    $value = array();
                }

                $this->data[$i][$name] = $value;
            }
        }else{
            foreach ($this->data as $key => $row) {
                $keyValues = array();

                foreach ($key as $value) {
                    $keyValues[] = $row[$value];
                }

                $this->data[$i][$name] = $value;
            }
        }

        return $this;
    }

    public function data($name = null, $value = null)
    {
        if (is_null($name) && is_null($value)) {
            return $this->data;
        }

        if (is_null($value)) {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        }

        $this->data[$name] = $value;

        return $this;
    }

    public function index($keys, $subArray = false)
    {
        return new \Elusim\Data\Index($this->data, $keys, $subArray);
    }

    public function column($name)
    {
        $values = array();

        foreach ($this->data as $row) {
            $values[] = $row[$name];
        }

        return $values;
    }

    public function toArray()
    {
        return $this->data;
    }
}
