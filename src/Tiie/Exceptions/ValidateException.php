<?php
namespace Tiie\Exceptions;

/**
 * Wyjątek wyrzucany w przypadku błednych danych.
 */
class ValidateException extends \Exception
{
    private $errors;

    public function __construct($errors = null, \Exception $previous = null)
    {
        parent::__construct("Validate Exception", 0, $previous);

        $this->errors = $errors;
    }

    public function errors()
    {
        return $this->errors;
    }
}

