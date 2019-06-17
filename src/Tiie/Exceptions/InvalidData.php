<?php
namespace Tiie\Exceptions;

class InvalidData extends \Exception
{
    private $errors;

    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct("Invalid data", 0, $previous);

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
