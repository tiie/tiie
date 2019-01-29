<?php
namespace Tiie\Data\Adapters\Commands\SQL;

class Common
{
    public function resolveTable($table)
    {
        if (is_string($table)) {
            $table = $this->removeDuplicateCharacters(trim($table), array(" "));

            $exploded = explode(" ", trim($table));

            if (count($exploded) == 1) {
                return array(
                    "table" => trim($exploded[0]),
                    "alias" => null,
                );
            } elseif (count($exploded) == 2) {
                return array(
                    "table" => trim($exploded[0]),
                    "alias" => trim($exploded[1]),
                );
            } elseif (count($exploded) == 3 && trim($exploded[1]) == "as") {
                return array(
                    "table" => trim($exploded[0]),
                    "alias" => trim($exploded[2]),
                );
            } else {
                trigger_error(E_USER_WARNING, "I can't decode '{$table}' at from.");

                return null;
            }
        } else if ($table instanceof Select) {
            return array(
                "table" => $table,
                "alias" => null,
            );
        } else if ($table instanceof Expr) {
            return array(
                "table" => $table,
                "alias" => null,
            );
        } else {
            return null;
        }
    }

    public function resolveColumn($column)
    {
        if (is_string($column)) {
            $column = $this->removeDuplicateCharacters(trim($column), array(" "));

            $exploded = explode(" ", $column);

            if (count($exploded) == 1) {
                return $this->resolveColumnName($exploded[0]);
            } elseif (count($exploded) == 2) {
                $result = $this->resolveColumnName($exploded[0]);

                $result["alias"] = $exploded[1];

                return $result;
            } elseif (count($exploded) == 3 && trim($exploded[1]) == "as") {
                $result = $this->resolveColumnName($exploded[0]);

                $result["alias"] = trim($exploded[2]);

                return $result;
            } else {
                trigger_error(E_USER_WARNING, "Incorrect form of column '{$column}'");

                return null;
            }
        } else if ($column instanceof Select) {
            return array(
                "table" => null,
                "column" => $column,
                "alias" => null,
            );
        } else if ($column instanceof Expr) {
            return array(
                "table" => null,
                "column" => $column,
                "alias" => null,
            );
        } else if ($column instanceof Where) {
            return array(
                "table" => null,
                "column" => $column,
                "alias" => null,
            );
        } else {
            trigger_error("Incorrect type of column", E_USER_WARNING);

            return null;
        }
    }

    public function resolveColumnName(string $name)
    {
        $exploded = explode('.', $name);

        if (count($exploded) == 1) {
            return array(
                "table" => null,
                "column" => $exploded[0],
                "alias" => null,
            );
        } else if (count($exploded) == 2) {
            return array(
                "table" => $exploded[0],
                "column" => $exploded[1],
                "alias" => null,
            );
        } else {
            trigger_error(E_USER_WARNING, "Incorrect form of column '{$name}'");

            return array(
                "table" => null,
                "column" => null,
                "alias" => null,
            );
        }
    }

    public function resolveOrder($order)
    {
        if (is_string($order)) {
            $column = $this->removeDuplicateCharacters(trim($order), array(" "));

            $exploded = explode(" ", $column);
            $resolved = $this->resolveColumnName($exploded[0]);

            // name asc
            // a.name
            if (count($exploded) == 1) {
                $resolved["type"] = "asc";

                return $resolved;
            } else if(count($exploded) == 2) {
                $resolved["type"] = $exploded[1];

                return $resolved;
            }
        } else {
            return array(
                "table" => null,
                "column" => null,
                "type" => null,
            );
        }
    }

    public function removeDuplicateCharacters(string $string, array $characters = array())
    {
        foreach ($characters as $character) {
            while(strpos($string, "{$character}{$character}") !== false)  {
                $string = str_replace("{$character}{$character}", "{$character}", $string);
            }
        }

        return $string;
    }

}
