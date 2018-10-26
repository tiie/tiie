<?php
namespace Topi\Data\Adapters;

class Result
{
    private $data;
    private $variables;

    function __construct($data = array(), array $variables = array())
    {
        $this->data = $data;
        $this->variables = $variables;
    }

    public function data(string $format = 'all')
    {
        return $this->data;
    }

    public function variables()
    {
        return $this->variables;
    }

    public function variable(string $name)
    {
        return array_key_exists($name, $this->variables) ? $this->variables[$name] : null;
    }

    public function get(string $name)
    {
        if (!is_array($this->data) || is_null($this->data)) {
            return null;
        }

        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    public function format(string $format = 'all')
    {
        // response format
        switch ($format) {
        case 'all':
            return $this->data;
        case 'row':
            if (empty($this->data)) {
                return null;
            }else{
                return $this->data[0];
            }
        case 'one':
            if (empty($this->data)) {
                return null;
            }else{
                $keys = array_keys($this->data);

                return $this->data[$keys[0]];
            }
        case 'col':
            $values = array();

            if (!empty($this->data)) {
                $index = array_keys($this->data[0])[0];

                foreach ($this->data as $row) {
                    $values[] = $row[$index];
                }
            }

            return $values;
        default:
            throw new \Exception("Unsported format {$format}.");

            break;
        }
    }
}
