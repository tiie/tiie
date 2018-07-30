<?php
namespace Topi\Filters;

class Trim implements \Topi\Filters\FilterInterface
{
    private $config = array();

    function __construct($config = array())
    {
        $this->config = array_replace(array(
            // 'flags' => ENT_QUOTES | ENT_HTML401,
            // 'encoding' => 'UTF-8'
        ), $config);
    }

    public static function description()
    {
    }

    public static function filter($value)
    {
        return trim($value);
    }
}
