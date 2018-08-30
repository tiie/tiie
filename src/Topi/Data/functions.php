<?php
namespace Topi\Data;

use Topi\Data\Adapters\Commands\SQL\Expr;

class functions
{
    public static function columnStr($value, array $config = array())
    {
        $d = null;

        if (is_string($value)) {
            if (strpos($value, '.') != false) {
                // table.column
                $t = explode('.', $value);

                return "{$config['quote']}{$t[0]}{$config['quote']}.{$config['quote']}{$t[1]}{$config['quote']}";
            }else{
                return "{$config['quote']}{$value}{$config['quote']}";
            }
        } elseif ($value instanceof Expr) {
            return $value->__toString();
        }

        $type = gettype($value);

        if (is_object($value)) {
            $type = get_class($value);
        }

        throw new \Exception(sprintf("Unsupported type of value %s", $type));
    }

    public static function isVector($array)
    {
        foreach (array_keys($array) as $key) {
            if (!is_numeric($key)) {
                return false;
            }
        }

        return true;
    }
}

