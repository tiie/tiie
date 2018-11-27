<?php
namespace Elusim\Exceptions\Http;

class Locked extends \Elusim\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 423, $previous);
    }
}
