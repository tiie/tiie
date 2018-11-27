<?php
namespace Elusim\Data\Model;

class Record implements RecordInterface
{
    /**
     * Reference to model of record.
     *
     * @var ModelInterface
     */
    private $model;

    /**
     * Key of id index.
     *
     * @var string
     */
    private $fieldId;

    /**
     * Init data.
     *
     * @var array
     */
    private $data;

    /**
     * Modyfied data.
     *
     * @var array
     */
    private $modyfied = array();

    function __construct(ModelInterface $model, array $data = array(), string $fieldId = 'id')
    {
        $this->model = $model;
        $this->fieldId = $fieldId;
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::id()
     */
    public function id() : ?string
    {
        return array_key_exists($this->fieldId, $this->data) ? $this->data[$this->fieldId] : null;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::run()
     */
    public function run(string $command, array $params = array()) : RecordInterface
    {
        if (!is_null($errors = $this->model->validate($this, $command, $params))) {
            throw new \Elusim\Exceptions\ValidateException($errors);
        }

        $this->model->run($this, $command);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::save()
     */
    public function save(array $params = array()) : RecordInterface
    {
        if (! is_null($errors = $this->model->validate('save'))) {
            throw new \Elusim\Exceptions\ValidateException($errors);
        }

        $this->model->save($this);

        return $this;
    }

    public function __debugInfo()
    {
        return $this->data;
    }

    public function setted(string $name, int $modyfied = 1) : int
    {
        if ($modyfied) {
            if (array_key_exists($name, $this->modyfied)) {
                return 1;
            }
        }

        return array_key_exists($name, $this->data);
    }

    public function data(int $modyfied = 1) : array
    {
        if ($modyfied) {
            return array_merge($this->data, $this->modyfied);
        } else {
            return $this->data;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::remove()
     */
    public function remove(array $params = array()) : RecordInterface
    {
        if (! is_null($errors = $this->model->validate('remove'))) {
            throw new \Elusim\Exceptions\ValidateException($errors);
        }

        $this->model->save($this);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::toArray()
     */
    public function toArray(array $params = array()): array
    {
        return $this->export($params);
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::export()
     */
    public function export(array $params = array()): array
    {
        $exported = array();

        foreach ($this->data as $key => $value) {
            if ($value instanceof RecordInterface) {
                $exported[$key] = $value->export();
            } else {
                $exported[$key] = $value;
            }
        }

        return $exported;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::set()
     */
    public function set(string $attribute, $value) : RecordInterface
    {
        $this->modyfied[$attribute] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::get()
     */
    public function get(string $attribute, int $modyfied = 1) : ?string
    {
        if (array_key_exists($attribute, $this->modyfied) && $modyfied) {
            return $this->modyfied[$attribute];
        }

        if (array_key_exists($attribute, $this->data)) {
            return $this->data[$attribute];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::modyfied()
     */
    public function modyfied() : array
    {
        return $this->modyfied;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::revert()
     */
    public function revert(): RecordInterface
    {
        $this->modyfied = array();

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Elusim\Data\Model\RecordInterface::is()
     */
    public function is(string $name): int
    {
        switch ($name) {
        case 'new':
            return is_null($this->id());
        default:
            throw new \Exception("Unsported feature {$name}");
        }
    }
}