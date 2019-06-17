<?php declare(strict_types=1);

namespace Tiie\Model;

use Tiie\Model\CreatorInterface;
use Tiie\Model\ModelInterface;

use Tiie\Model\Commands\CreateRecord as CommandCreateRecord;
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
            $this->record = $this->model->factory(ModelInterface::FACTORY_TYPE_RECORD, $this->data);
        }

        return $this->model->run(new CommandCreateRecord($this->record), $params);
    }

    public function setData(array $data, int $merge = 1) : void
    {
        if ($merge) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
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
