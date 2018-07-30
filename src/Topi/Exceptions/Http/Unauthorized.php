<?php
namespace Topi\Exceptions\Http;

class Unauthorized extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 401, $previous);
    }
}