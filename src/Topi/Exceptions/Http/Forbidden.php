<?php
namespace Topi\Exceptions\Http;

class Forbidden extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 403, $previous);
    }
}