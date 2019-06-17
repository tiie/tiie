<?php declare(strict_types=1);

namespace Tiie\Model;

use Tiie\Commands\CommandInterface;
use Tiie\Commands\Result\ResultInterface;

interface RecordInterface
{
    /**
     * Return Id of record or null if record is new.
     *
     * @return string|NULL
     */
    public function getId() : ?string;

    /**
     * Run specific command on record.
     *
     * @param Tiie\Commands\CommandInterface $command
     * @param array $param
     * @return RecordInterface
     */
    public function run(CommandInterface $command, array $params = array()) : ?ResultInterface;

    /**
     * Check if given command can be run.
     *
     * @param Tiie\Commands\CommandInterface $command
     * @param array $param
     * @param null|array
     */
    public function validate(CommandInterface $command, array $params = array()) : ?array;

    /**
     * Save record.
     *
     * @param array $params
     * @return RecordInterface
     */
    public function save(array $params = array()) : ?ResultInterface;

    /**
     * Remove record.
     *
     * @param array $params
     * @return RecordInterface
     */
    public function remove(array $params = array()) : ?ResultInterface;

    /**
     * Return value of attribute.
     *
     * @param string $attribute
     * @param bool $modyfied
     * @return string
     */
    public function get(string $attribute, bool $modyfied = true);

    /**
     * Set attribute with given value.
     *
     * @param string $attribute
     * @param mixed $value
     * @return RecordInterface
     */
    public function set(string $attribute, $value) : RecordInterface;

    /**
     * Return array with modyfied attributes.
     * @return array
     */
    public function modyfied() : array;

    /**
     * Revert all modyfied attribute.
     *
     * @return bool
     */
    public function revert() : bool;

    /**
     * Export record to array. This method is used by other method like
     * toArray() or toXml() to use record.
     *
     * @param array $params
     * @return array
     */
    public function export(array $params = array()) : array;

    public function getData(bool $modyfied = true) : array;

    public function setted(string $name, bool $modyfied = true) : bool;

    /**
     * Export record to array.
     *
     * @param array $params
     * @return array
     */
    public function toArray(array $params = array()) : array;

    /**
     * Methoda should check whether the record meets a certain feature.
     *
     * @param string $name
     * @return bool
     */
    public function is(string $name) : bool;
}
