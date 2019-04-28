<?php declare(strict_types=1);

namespace Tiie\Commands\Exceptions;

use Exception;

/**
 * Validate exceptions. Exception is thrown when command can not be executed.
 *
 * @package Tiie\Commands\Exceptions
 */
class ValidationFailed extends Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @param array|null $errors
     * @param Exception|null $previous
     */
    public function __construct(array $errors = array(), \Exception $previous = null)
    {
        parent::__construct("Validate Exception", 0, $previous);

        $this->errors = $errors;
    }

    /**
     * Return list of validation errors.
     *
     * @return array
     */
    public function errors() : array
    {
        return $this->errors;
    }
}
