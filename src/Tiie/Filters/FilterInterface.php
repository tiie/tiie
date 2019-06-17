<?php
namespace Tiie\Filters;

interface FilterInterface
{
    public function getDescription() : ?string;
    public function filter(string $value) : ?string;
}
