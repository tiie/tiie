<?php
namespace Elusim\Exceptions\Http;

class UnsupportedMediaType extends \Elusim\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 415, $previous);
    }
}
