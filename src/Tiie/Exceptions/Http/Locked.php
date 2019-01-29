<?php
namespace Tiie\Exceptions\Http;

class Locked extends \Tiie\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 423, $previous);
    }
}
