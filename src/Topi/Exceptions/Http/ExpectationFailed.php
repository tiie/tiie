<?php
namespace Topi\Exceptions\Http;

class ExpectationFailed extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 417, $previous);
    }
}