<?php
namespace Tiie\Exceptions\Http;

class BadRequest extends \Tiie\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 400, $previous);
    }
}
