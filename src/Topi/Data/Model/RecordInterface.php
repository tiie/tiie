<?php
namespace Elusim\Data\Model;

interface RecordInterface
{
    /**
     * Return Id of record or null if record is new.
     *
     * @return string|NULL
     */
    public function id() : ?string;

    /**
     * Run specific command on record.
     *
     * @param string $command
     * @param array $param
     * @return RecordInterface
     */
    public function run(string $command, array $param = array()) : RecordInterface;

    /**
     * Save record.
     *
     * @param array $params
     * @return RecordInterface
     */
    public function save(array $params = array()) : RecordInterface;

    /**
     * Remove record.
     *
     * @param array $params
     * @return RecordInterface
     */
    public function remove(array $params = array()) : RecordInterface;

    /**
     * Return value of attribute.
     *
     * @param string $attribute
     * @param int $modyfied
     * @return string
     */
    public function get(string $attribute, int $modyfied = 1) : ?string;

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
     * @return RecordInterface
     */
    public function revert() : RecordInterface;

    /**
     * Export record to array. This method is used by other method like
     * toArray() or toXml() to use record.
     *
     * @param array $params
     * @return array
     */
    public function export(array $params = array()) : array;

    public function data(int $modyfied = 1) : array;

    public function setted(string $name, int $modyfied = 1) : int;

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
     * @return int 0|1
     */
    public function is(string $name) : int;
}
