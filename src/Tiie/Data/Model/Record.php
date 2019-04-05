<?php declare(strict_types=1);

namespace Tiie\Data\Model;

use Tiie\Data\Model\ModelInterface;
use Tiie\Data\Model\RecordInterface;
use Tiie\Data\Model\CreatorInterface;

use Tiie\Data\Model\Commands\RemoveRecord as CommandRemoveRecord;
use Tiie\Data\Model\Commands\SaveRecord as CommandSaveRecord;
use Tiie\Data\Model\Commands\CreateRecord as CommandCreateRecord;

use Tiie\Commands\Result\ResultInterface;
use Tiie\Commands\CommandInterface;

use ArrayAccess;

class Record implements RecordInterface, ArrayAccess
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
    private $keyId;

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

    function __construct(ModelInterface $model, array $data = array(), string $keyId = "id")
    {
        $this->model = $model;
        $this->data = $data;
        $this->keyId = $keyId;
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::id()
     */
    public function id() : ?string
    {
        return array_key_exists($this->keyId, $this->data) ? $this->data[$this->keyId] : null;
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::run()
     */
    public function run(CommandInterface $command, array $params = array()) : ?ResultInterface
    {
        trigger_error(sprintf("There is no implementation of %s command.", get_class($command)), E_USER_WARNING);

        return null;
    }

    public function validate(CommandInterface $command, array $params = array()) : ?array
    {
        return $this->model->validate($command, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Data\Model\RecordInterface::save()
     */
    public function save(array $params = array()) : ?ResultInterface
    {
        return $this->model->run(new CommandSaveRecord($this), $params);
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::remove()
     */
    public function remove(array $params = array()) : ?ResultInterface
    {
        return $this->model->run(new CommandRemoveRecord($this), $params);
    }

    public function __debugInfo()
    {
        return $this->data;
    }

    public function setted(string $name, bool $modyfied = true) : bool
    {
        if ($modyfied) {
            if (array_key_exists($name, $this->modyfied)) {
                return 1;
            }
        }

        return array_key_exists($name, $this->data);
    }

    public function data(bool $modyfied = true) : array
    {
        if ($modyfied) {
            return array_merge($this->data, $this->modyfied);
        } else {
            return $this->data;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::toArray()
     */
    public function toArray(array $params = array()) : array
    {
        return $this->export($params);
    }

    public function toXML(array $params = array()) : string
    {
        trigger_error("Implement toXML method.", E_USER_WARNING);

        return "";
    }

    public function toJSON(array $params = array()) : string
    {
        return json_encode($this->toArray($params));
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::export()
     */
    public function export(array $params = array()) : array
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
     * @see \Tiie\Data\Model\RecordInterface::set()
     */
    public function set(string $attribute, $value) : RecordInterface
    {
        $this->modyfied[$attribute] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::get()
     */
    public function get(string $attribute, bool $modyfied = true)
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
     * @see \Tiie\Data\Model\RecordInterface::modyfied()
     */
    public function modyfied() : array
    {
        return $this->modyfied;
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::revert()
     */
    public function revert() : bool
    {
        $this->modyfied = array();

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Tiie\Data\Model\RecordInterface::is()
     */
    public function is(string $name) : bool
    {
        switch ($name) {
        case 'new':
            return is_null($this->id());
        default:
            trigger_error("Unsported feature {$name}.", E_USER_NOTICE);

            return false;
        }
    }

    // ArrayAccess
    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetExists($offset) {
        if (array_key_exists($offset, $this->modyfied)) {
            return true;
        }

        if (array_key_exists($offset, $this->data)) {
            return true;
        }

        return false;
    }

    public function offsetUnset($offset) {
        unset($this->modyfied[$offset]);
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        if (array_key_exists($offset, $this->modyfied)) {
            return $this->modyfied[$offset];
        }

        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }

        return null;
    }
}
