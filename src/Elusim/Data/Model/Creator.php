<?php
namespace Elusim\Data\Model;

use Elusim\Data\Model\CreatorInterface;
use Elusim\Data\Model\ModelInterface;

class Creator implements CreatorInterface
{
    private $model;
    private $data = array();
    private $record = null;

    function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    public function create(array $params = array()) : ?string
    {
        if (is_null($this->record)) {
            $this->record = $this->model->createRecord($this->data);
        }

        return $this->model->create($this->record, $params);
    }

    public function data(array $data, int $merge = 1) : CreatorInterface
    {
        if ($merge) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }

        return $this;
    }

    public function set(string $name, $value) : CreatorInterface
    {
        $this->data[$name] = $value;

        $this->record = null;

        return $this;
    }

    public function get(string $name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    public function validate(array $params = array()) : ?array
    {
        if (is_null($this->record)) {
            $this->record = $this->model->createRecord($this->data);
        }

        return $this->model->validate($this->record, ModelInterface::PROCESS_CREATING, $params);
    }
}
