<?php
namespace Tiie\Filters;

class FirstCharacterUppercase extends Filter
{
    public function filter(string $value) : ?string
    {
        return ucfirst($value);
    }
}
