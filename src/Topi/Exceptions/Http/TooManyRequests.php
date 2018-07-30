<?php
namespace Topi\Exceptions\Http;

class TooManyRequests extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 429, $previous);
    }
}