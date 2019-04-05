<?php declare(strict_types=1);
namespace Tiie\Commands\Exceptions;

use Exception;

class ValidationFailed extends Exception
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
