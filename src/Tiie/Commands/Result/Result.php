<?php declare(strict_types=1);

namespace Tiie\Commands\Result;

/**
 * Standard implementation of result for command.
 *
 * @package Tiie\Commands\Result
 */
class Result implements ResultInterface
{
    /**
     * @var null
     */
    private $value;

    function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->value;
    }
}
