<?php
namespace Tiie\Filters;

interface FilterInterface
{
    public function description() : ?string;
    public function filter(string $value) : ?string;
}
