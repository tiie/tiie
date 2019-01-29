<?php
namespace Tiie\Http\Headers;

class Header
{
    private $name;
    private $value;

    function __construct(string $name, string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Set value of 'value' if value is given or return present value of
     * 'value'.
     *
     * @param string $value
     * @return $this|string
     */
    public function value(string $value = null)
    {
        if (func_num_args() == 0) {
            return $this->value;
        } else {
            $this->value = $value;

            return $this;
        }
    }

    public function name(string $name = null)
    {
        if (func_num_args() == 0) {
            return $this->name;
        } else {
            $this->name = $name;

            return $this;
        }
    }
}
