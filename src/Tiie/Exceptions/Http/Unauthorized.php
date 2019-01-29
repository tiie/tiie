<?php
namespace Tiie\Exceptions\Http;

class Unauthorized extends \Tiie\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 401, $previous);
    }
}
