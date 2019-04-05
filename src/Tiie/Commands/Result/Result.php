<?php declare(strict_types=1);

namespace Tiie\Commands\Result;

use Tiie\Commands\Result\ResultInterface;

class Result implements ResultInterface
{
    private $value;

    function __construct($value = null)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
