<?php
namespace Tiie\Data;

/**
 * @package Tiie\Data
 */
class Index
{
    /**
     * @var array
     */
    private $index = array();

    /**
     * Index constructor.
     *
     * @param array $data
     * @param array $keys
     * @param bool $subArray
     *
     * @throws \Exception
     */
    function __construct(array $data = array(), array $keys = array(), bool $subArray = true)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }

        $key = null;

        if (count($keys) == 1) {
            $key = $keys[0];
        }

        if (count($keys) == 0) {
            throw new \Exception("Index needs at least one column to create.");
        }

        if (!is_null($key)) {
            foreach ($data as $row) {
                if ($subArray) {
                    $this->index[$row[$key]][] = $row;
                }else{
                    $this->index[$row[$key]] = $row;
                }
            }
        }else{
            foreach ($data as $row) {
                $key = "";
                foreach ($keys as $skey) {
                    $key .= "-".$row[$skey];
                }

                $key = trim($key, '-');

                if ($subArray) {
                    $this->index[$key][] = $row;
                }else{
                    $this->index[$key] = $row;
                }
            }
        }
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (is_array($key)) {
            $key = implode('-', $key);
        }

        if (!isset($this->index[$key])) {
            return null;
        }

        return $this->index[$key];
    }
}
