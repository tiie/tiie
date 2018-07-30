<?php
namespace Topi\Exceptions\Http;

class Locked extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 423, $previous);
    }
}