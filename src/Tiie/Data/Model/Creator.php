<?php declare(strict_types=1);

namespace Tiie\Data\Model;

use Tiie\Data\Model\CreatorInterface;
use Tiie\Data\Model\ModelInterface;

use Tiie\Data\Model\Commands\CreateRecord as CommandCreateRecord;
use Tiie\Commands\Result\ResultInterface;

class Creator implements CreatorInterface
{
    private $model;
    private $data = array();
    private $record = null;

    function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    public function create(array $params = array()) : ?ResultInterface
    {
        if (is_null($this->record)) {
            // $this->record = $this->model->createRecord($this->data);
            $this->record = $this->model->factory(ModelInterface::FACTORY_TYPE_RECORD, $this->data);
        }

        return $this->model->run(new CommandCreateRecord($this->record), $params);
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

        return $this->model->validate($this->record, ModelInterface::COMMAND_CREATE, $params);
    }
}
