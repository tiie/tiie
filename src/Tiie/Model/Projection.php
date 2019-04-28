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
    public function params(array $params = null)
    {
        if(func_num_args() == 0) {
            return $this->params;
        } else {
            $this->params = $params;

            return $this;
        }
    }

    public function param(string $name, $value = null)
    {
        if(func_num_args() == 1) {
            return array_key_exists($name, $this->params) ? $this->params[$name] : null;
        } else {
            $this->params[$name] = $value;

            return $this;
        }
    }

    public function field(string $field)
    {

    }

    public function fields(array $fields = null)
    {
        if(func_num_args() == 0) {
            return $this->fields;
        } else {
            $this->fields = $fields;

            return $this;
        }
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

    public function size($size = null)
    {
        if(func_num_args() == 0) {
            return $this->size;
        } else {
            $this->size = $size;

            return $this;
        }
    }

    public function page($page = null)
    {
        if(func_num_args() == 0) {
            return $this->page;
        } else {
            $this->page = $page;

            return $this;
        }
    }
}
