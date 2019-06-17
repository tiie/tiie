<?php
namespace Tiie\Model;

use Tiie\Model\Records;

class Projection
{
    private $params = array();
    private $fields = array();
    private $sort = array();
    private $size = null;
    private $page = 0;
    private $model;

    function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    public function find() : Records
    {
        return $this->model->find(
            $this->params,
            $this->fields,
            $this->sort,
            $this->size,
            $this->page
        );
    }

    // func_num_args
    public function setParams(array $params) : void
    {
        $this->params = $params;
    }

    public function setParam(string $name, $value) : void
    {
        $this->params[$name] = $value;
    }

    public function getParam(string $name)
    {
        return array_key_exists($name, $this->params) ? $this->params[$name] : null;
    }

    public function setFields(array $fields) : void
    {
        $this->fields = $fields;
    }

    public function sort($sort = null)
    {
        if(func_num_args() == 0) {
            return $this->sort;
        } else {
            $this->sort = $sort;

            return $this;
        }
    }

    public function setSize($size) : void
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setPage($page) : void
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }
}
