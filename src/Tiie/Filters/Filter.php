<?php
namespace Tiie\Filters;

use Tiie\Filters\FilterInterface;

class Filter implements FilterInterface
{
    public function getDescription() : ?string
    {
        return null;
    }

    public function filter(string $value) : ?string
    {
        return $value;
    }
}
