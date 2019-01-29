<?php
namespace Tiie\Filters;

interface FilterInterface
{
    public static function description();
    public static function filter($value);
}
