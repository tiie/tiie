<?php
namespace Topi\Exceptions\Http;

class PaymentRequired extends \Topi\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 402, $previous);
    }
}