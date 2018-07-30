<?php
namespace Topi\Data\Model;

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
     * @see \Topi\Data\Model\RecordInterface::id()
     */
    public function id() : ?string
    {
        return array_key_exists($this->fieldId, $this->data) ? $this->data[$this->fieldId] : null;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::run()
     */
    public function run(string $command, array $param = array()) : RecordInterface
    {
        if (! is_null($errors = $this->model->validate($command))) {
            throw new \Topi\Exceptions\ValidateException($errors);
        }

        $this->model->run($this, $command);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::save()
     */
    public function save(array $params = array()) : RecordInterface
    {
        if (! is_null($errors = $this->model->validate('save'))) {
            throw new \Topi\Exceptions\ValidateException($errors);
        }

        $this->model->save($this);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::remove()
     */
    public function remove(array $params = array()) : RecordInterface
    {
        if (! is_null($errors = $this->model->validate('remove'))) {
            throw new \Topi\Exceptions\ValidateException($errors);
        }

        $this->model->save($this);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::export()
     */
    public function export(array $params = array()): array
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::toArray()
     */
    public function toArray(array $params = array()): array
    {
        return $this->export($params);
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::set()
     */
    public function set(string $attribute, $value) : RecordInterface
    {
        $this->modyfied[$attribute] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::get()
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
     * @see \Topi\Data\Model\RecordInterface::modyfied()
     */
    public function modyfied() : array
    {
        return $this->modyfied;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::revert()
     */
    public function revert(): RecordInterface
    {
        $this->modyfied = array();

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Topi\Data\Model\RecordInterface::is()
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
