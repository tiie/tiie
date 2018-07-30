<?php
namespace Topi\Filters;

interface FilterInterface
{
    public static function description();
    public static function filter($value);
}
