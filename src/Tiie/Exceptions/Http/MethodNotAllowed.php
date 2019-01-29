<?php
namespace Tiie\Exceptions\Http;

class MethodNotAllowed extends \Tiie\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 405, $previous);
    }
}
