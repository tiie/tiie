<?php
namespace Elusim\Exceptions\Http;

class FailedDependency extends \Elusim\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 424, $previous);
    }
}
