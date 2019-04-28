<?php
namespace Tiie\Model;

use Tiie\Commands\Result\ResultInterface;

interface CreatorInterface
{
    /**
     * Create new record and return id of record.
     *
     * @param array $params
     * @return string
     */
    public function create(array $params = array()) : ?ResultInterface;

    /**
     * Set value of attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value) : CreatorInterface;

    /**
     * Return value of attribute.
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name);

    public function data(array $data, int $merge = 1) : CreatorInterface;

    /**
     * Validate creating of model.
     *
     * @param array $params
     * @return array|null
     */
    public function validate(array $params = array()) : ?array;
}
