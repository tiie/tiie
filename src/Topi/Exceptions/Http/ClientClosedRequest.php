<?php
namespace Elusim\Exceptions\Http;

class ClientClosedRequest extends \Elusim\Exceptions\Http\Base
{
    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct($errors, 499, $previous);
    }
}
