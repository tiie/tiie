<?php
namespace Tiie\Filters;

class FirstCharacterLowercase extends Filter
{
    public function filter(string $value) : ?string
    {
        return lcfirst($value);
    }
}
