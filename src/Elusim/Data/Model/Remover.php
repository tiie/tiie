<?php
namespace Elusim\Data\Model;

class Records
{
    private $records = array();
    private $adapter;

    function __construct($adapter)
    {
        $this->adapter = $adapter;
    }
}