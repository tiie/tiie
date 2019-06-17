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

    public function setValue(string $value) : void
    {
        $this->value = $value;
    }

    public function getValue() : ?string
    {
        return $this->value;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
