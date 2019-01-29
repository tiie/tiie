<?php
namespace Tiie\Exceptions\Http;

class RequestTimeout extends \Tiie\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 408, $previous);
    }
}
