<?php
namespace \Elusim\Data\Filters;

interface FilterInterface
{
    /**
     * Filter given value.
     *
     * @param mixed $value
     * @return null|mixed Return value after filter.
     */
    public function filter($value);
}
