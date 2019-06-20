<?php
namespace Tiie\Filters;

class Trim implements FilterInterface
{
    private $config = array();

    function __construct($config = array())
    {
        $this->config = array_replace(array(
            // 'flags' => ENT_QUOTES | ENT_HTML401,
            // 'encoding' => 'UTF-8'
        ), $config);
    }

    public function getDescription() : ?string
    {
        return null;
    }

    public function filter(string $value) : ?string
    {
        return trim($value);
    }
}
