<?php
namespace Elusim\Utils;

class ArrayUtils {
    public function column(array $array, string $column)
    {
        $values = array();

        foreach ($array as $key => $v) {
            if (array_key_exists($column, $v)) {
                $values[] = $v[$column];
            }
        }

        return $values;
    }
}
