<?php
namespace Elusim\Exceptions\Http;

class NotFound extends \Elusim\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 404, $previous);
    }
}
