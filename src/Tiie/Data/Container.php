<?php
namespace Tiie\Data;

use ArrayAccess;

/**
 * A general data object that provides many methods to simplify work.
 *
 * @package Tiie\Data
 */
class Container implements ArrayAccess
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Init container with given data.
     *
     * @param array $data
     */
    function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Magic setter.
     *
     * @param string $name
     *
     * @param $value
     */
    public function __set(string $name , $value) : void
    {
        $this->set($name, $value);
    }

    /**
     * Magic getter.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Magic isset.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return isset($this->data[$name]);
    }

    /**
     * Magic unsetter. Unset property of container.
     *
     * @param string $name
     */
    public function __unset(string $name) : void
    {
        unset($this->data["name"]);
    }

    /**
     * Return data under given name.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     * Set data under given name.
     *
     * @param string $name
     * @param $value
     *
     * @return Container
     */
    public function set(string $name, $value) : Container
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Return array of data.
     *
     * @return array
     */
    public function toArray() : array
    {
        return $this->data;
    }

    /**
     * Return boolean value for given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function is(string $name) : bool
    {
        return empty($this->get($name)) ? false : true;
    }

    /**
     * Merget data with given input. Input can be array or other container.
     *
     * @param Container|array input
     *
     * @return Container
     */
    public function merge($input) : Container
    {
        if ($input instanceof Container) {
            $input = $input->toArray();
        }

        $this->data = array_merge($this->data, $input);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}
