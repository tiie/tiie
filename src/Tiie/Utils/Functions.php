<?php
namespace Tiie\Utils;

class Functions
{
    public static function type($variable)
    {
        $type = gettype($variable);

        if ($type == "object") {
            return $type.":".get_class($variable);
        } else {
            return $type;
        }
    }

    public static function inline($variable)
    {
        if (!is_string($variable)) {
            $variable = var_export($variable, 1);
        }

        return implode("", explode("\n", $variable));
    }
}

