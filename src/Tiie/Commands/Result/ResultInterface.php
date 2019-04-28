<?php declare(strict_types=1);

namespace Tiie\Commands\Result;

/**
 * The command after execution can return the result. The result
 * should implement a specific interface to reduce the return value.
 *
 * @package Tiie\Commands\Result
 */
interface ResultInterface
{
    /**
     * Returns given value.
     * @return mixed
     */
    public function value();

    /**
     * Returns value as string.
     * @return string
     */
    public function __toString() : string;
}
