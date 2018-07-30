<?php
namespace Topi\Data\Adapters\Commands\SQL;

class Where extends \Topi\Data\Adapters\Commands\Command
{
    private $where = null;
    private $pw = null;

    function __construct()
    {
        $this->where = new \stdClass();

        $this->where->type = 'brakets';
        $this->where->operator = 'and';
        $this->where->childs = array();

        $this->pw = $this->where;
    }

    public function brackets($function, $scope = null)
    {
        $child = new \stdClass();
        $child->type = 'brakets';
        $child->operator = 'and';
        $child->childs = array();

        if (is_null($scope)) {
            $scope = $this;
        }

        $this->pw->childs[] = $child;

        // Zapisuje pw
        $pwt = $this->pw;
        $this->pw = $child;

        call_user_func_array($function, array($scope));

        // Cofam pw do poprzedniego stanu
        $this->pw = $pwt;

        return $this;
    }

    public function ando()
    {
        $this->pw->operator = 'and';

        return $this;
    }

    public function oro()
    {
        $this->pw->operator = 'or';

        return $this;
    }

    public function params(array $values = array(), array $fields = array())
    {
        $re = '/^(.*?)-(not-in|in|is-not-null|is-null|start-with|end-with|contains|not-like|like|not-equal|equal|lower-than-equal|lower-than|greater-than-equal|greater-than|between)$/m';
        $re2 = '/^(.*?)(NotIn|In|IsNotNull|IsNull|StartWith|EndWith|Contains|NotLike|Like|NotEqual|Equal|LowerThanEqual|LowerThan|GreaterThanEqual|GreaterThan|Between)$/m';

        $columns = array();

        foreach ($values as $key => $value) {
            preg_match_all($re2, $key, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                preg_match_all($re, $key, $matches, PREG_SET_ORDER, 0);
            }

            if (empty($matches)) {
                $column = $this->dashToCamelCase($key);

                if (array_key_exists($column, $fields)) {
                    if (is_array($value)) {
                        $this->in($column, $value);
                    } else {
                        $this->eq($column, $value);
                    }

                    return $this;
                } else {
                    continue;
                }
            }

            $fielddash = $matches[0][1];
            $operation = $matches[0][2];

            $allowed = array();
            $excluded = array();

            $allowedKey = array_key_exists("{$fielddash}", $fields);
            $excludeKey = array_key_exists("exclude:{$fielddash}", $fields);

            if (!$allowedKey && !$excludeKey) {
                continue;
            }

            if ($allowedKey) {
                if ($fields[$fielddash] == 'all') {
                    $allowed = array(
                        'not-in',
                        'NotIn',

                        'in',
                        'In',

                        'is-not-null',
                        'IsNotNull',

                        'is-null',
                        'IsNull',

                        'start-with',
                        'StartWith',

                        'end-with',
                        'EndWith',

                        'contains',
                        'Contains',

                        'not-like',
                        'NotLike',

                        'like',
                        'Like',

                        'not-equal',
                        'NotEqual',

                        'equal',
                        'Equal',

                        'lower-than-equal',
                        'LowerThanEqual',

                        'lower-than',
                        'LowerThan',

                        'greater-than-equal',
                        'GreaterThanEqual',

                        'greater-than',
                        'GreaterThan',

                        'between',
                        'Between',
                    );
                } else {
                    $allowed = $fields[$fielddash];
                }
            }

            if ($excludeKey) {
                $excludeKey = $fields["exclude:{$fielddash}"];
            }

            if (in_array($operation, $excluded)) {
                continue;
            }

            if (!in_array($operation, $allowed)) {
                continue;
            }

            $column = null;

            if (isset($columns[$fielddash])) {
                $column = $columns[$fielddash];
            } else {
                $column = $columns[$fielddash] = $this->dashToCamelCase($fielddash);
            }

            switch($matches[0][2]) {
            case 'not-in':
            case 'NotIn':
                $this->notIn($column, $value);
                break;
            case 'in':
            case 'In':
                $this->in($column, $value);
                break;
            case 'is-not-null':
            case 'IsNotNull':
                $this->isNotNull($column);
                break;
            case 'is-null':
            case 'IsNull':
                $this->isNull($column);
                break;
            case 'start-with':
            case 'StartWith':
                $this->startWith($column, $value);
                break;
            case 'end-with':
            case 'EndWith':
                $this->endWith($column, $value);
                break;
            case 'contains':
            case 'Contains':
                $this->contains($column, $value);
                break;
            case 'not-like':
            case 'NotLike':
                $this->notLike($column, $value);
                break;
            case 'like':
            case 'Like':
                $this->like($column, $value);
                break;
            case 'not-equal':
            case 'NotEqual':
                $this->neq($column, $value);
                break;
            case 'equal':
            case 'Equal':
                $this->eq($column, $value);
                break;
            case 'lower-than-equal':
            case 'LowerThanEqual':
                $this->lte($column, $value);
                break;
            case 'lower-than':
            case 'LowerThan':
                $this->lt($column, $value);
                break;
            case 'greater-than-equal':
            case 'GreaterThanEqual':
                $this->gte($column, $value);
                break;
            case 'greater-than':
            case 'GreaterThan':
                $this->gt($column, $value);
                break;
            case 'between':
            case 'Between':
                if (is_string($value)) {
                    $value = explode(',', $value);

                    $this->between($column, $value[0], $value[1]);
                } else {
                    if (array_key_exists('from', $value) && array_key_exists('to', $value)) {
                        $this->between($column, $value['from'], $value['to']);
                    }
                }

                break;
            }
        }

        return $this;
    }

    private function dashToCamelCase(string $name)
    {
        $name = explode("-", $name);

        foreach ($name as $k => $value) {
            if ($k == 0) {
                continue;
            }

            $name[$k] = ucfirst($value);
        }

        return implode('', $name);
    }

    // todo Zwracana wartosc w phpDoc
    // Trzeba sprawdzić co powiniennem zwracac w dokumentacji metody jeśli
    // zwracam thisa.

    /**
     * Metoda pozwala na wczytanie warynków, na postawie odpowiedniej struktury
     * danych.
     *
     * Użycie separatora
     *     $select->conditions('name-like', 'Pawel');
     *     $select->conditions('name-in', array());
     *
     * Użycie struktury
     *
     *
     * @param string $column
     * @param mixed $conditions
     * @param array $params
     *     + operator
     *
     * @return $this
     */
    public function conditions($column, $conditions, array $params = array())
    {
        $params['operator'] = !isset($params['operator']) ? 'in' : $params['operator'];

        $value = array();

        // Przypadek w którym struktura definiuje operator, w pozostałych
        // przypadkach staram się odczytać operator
        if (is_array($conditions)) {
            if (isset($conditions['from']) || isset($conditions['to'])) {
                if (isset($conditions['from']) && isset($conditions['to'])) {
                    $this->between($column, $conditions['from'], $conditions['to']);
                }elseif(isset($conditions['from'])) {
                    $this->gte($column, $conditions['from']);
                }elseif(isset($conditions['to'])) {
                    $this->lte($column, $conditions['to']);
                }

                return $this;
            }
        }

        if (
               is_numeric($conditions)
            || is_string($conditions)
            || is_null($conditions)
        ) {
            $value[] = $conditions;
        }elseif(is_array($conditions)){
            // A -> array(1,2,3,4)
            // B -> array(
            //         'id' => 1,
            //      )
            // C    array(
            //          'from' => 1,
            //          'to' => 1,
            //      )
            // D    array(
            //          array(
            //              'id' => 1,
            //          ),
            //          array(
            //              'id' => 2,
            //          ),
            //          ...
            //      )

            $isvector = 1;

            foreach (array_keys($conditions) as $key) {
                if (!is_numeric($key)) {
                    $isvector = 0;
                    break;
                }
            }

            if ($isvector) {
                foreach ($conditions as $key => $v) {
                    if (is_numeric($v) || is_string($v)) {
                        $value[] = $v;
                    }elseif(is_array($v)){
                        if (isset($v['id'])) {
                            if (is_numeric($v['id']) || is_string($v['id'])) {
                                $value[] = $v['id'];
                            }
                        }
                    }
                }
            }else{
                if (isset($conditions['id'])) {
                    if (is_numeric($conditions['id']) || is_string($conditions['id'])) {
                        $value[] = $conditions['id'];
                    }
                }
            }
        }

        switch ($params['operator']) {
        case 'in':
            $this->in($column, $value);

            break;
        case 'contains':

            // Robimy nawias ze wszystkimi ORAMI
            $this->brackets(function($where) use ($value, $column){
                $where->oro();

                foreach ($value as $v) {
                    $where->contains($column, $v);
                }
            });

            break;
        default:
            throw new \Exception("Unsupported operator.");
        }

        return $this;
    }

    public function in($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'in';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function notIn($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'notIn';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function isNull($column)
    {
        $child = new \stdClass();
        $child->type = 'isNull';
        $child->column = $column;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function isNotNull($column)
    {
        $child = new \stdClass();
        $child->type = 'isNotNull';
        $child->column = $column;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function startWith($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'startWith';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function endWith($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'endWith';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function contains($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'contains';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function like($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'like';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function notLike($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'notLike';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function eq($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'eq';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function neq($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'neq';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function lt($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'lt';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function lte($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'lte';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function gt($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'gt';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function gte($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'gte';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function expr($expr)
    {
        $child = new \stdClass();
        $child->type = 'expr';
        $child->expr = $expr;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function exists($value)
    {
        $child = new \stdClass();
        $child->type = 'exists';
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function notExists($value)
    {
        $child = new \stdClass();
        $child->type = 'notExists';
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function between($column, $begin, $end)
    {
        $child = new \stdClass();
        $child->type = 'between';
        $child->column = $column;
        $child->begin = $begin;
        $child->end = $end;

        $this->pw->childs[] = $child;

        return $this;
    }

    public function build(array $params = array())
    {
        $command = new \Topi\Data\Adapters\Commands\BuiltCommand();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        if (count($this->pw->childs) > 0) {
            $command->command("where {$this->buildBrakets($this->pw, $command, $params)}");

            return $command;
        }else{
            return $command;
        }
    }

    private function buildBrakets(\stdClass $brakets, \Topi\Data\Adapters\Commands\BuiltCommand $command, array $params)
    {
        $sql = "";

        foreach ($brakets->childs as $child) {
            switch ($child->type) {
            case 'brakets':
                $sql .= "({$this->buildBrakets($child, $command, $params)}) {$brakets->operator} ";
                break;
            case 'in':
            case 'notIn':
                $s = "";
                $column = \Topi\Data\functions::columnStr($child->column, $params);

                if (is_array($child->value)) {
                    foreach ($child->value as $value) {
                        if (is_numeric($value)) {
                            $s .= "{$value},";
                        }else{
                            $uid = $this->uid();
                            $s .= ":{$uid},";
                            $command->param($uid, $value);
                        }
                    }

                    $s = trim($s, ',');
                // todo Dorobic obsluge Selecta i Command jako wartosc dla in()
                // }elseif($child->value instanceof \Topi\Data\Statements\Statement){
                //     $s = $child->value->slq();
                //     $command->merge($child->value->binds());
                // }elseif($child->value instanceof \Topi\Data\Adapters\Commands\SQL\Select){
                //     $t = $child->value->build();
                //     $s = $t->slq();
                //     $command->merge($t->binds());
                }else{
                    throw new \Exception(sprintf("Unsupported type of in value %s", gettype($child->value)));
                }

                $o = 'in';

                if ($child->type == 'notIn') {
                    $o = 'not in';
                }

                if (empty($s)) {
                    $sql .= "(1=2) {$brakets->operator} ";
                }else{
                    $sql .= "{$column} {$o}({$s}) {$brakets->operator} ";
                }

                break;
            case 'isNull':
            case 'isNotNull':
                $s = "";
                $column = \Topi\Data\functions::columnStr($child->column, $params);

                $o = 'is null';

                if ($child->type == 'isNotNull') {
                    $o = 'is not null';
                }

                $sql .= "{$column} {$o} {$brakets->operator} ";

                break;
            case 'startWith':
            case 'endWith':
            case 'contains':
                if (!is_string($child->value)) {
                    throw new \Exception(sprintf("Unsupported type of % value %s", $child->type, gettype($child->value)));
                }

                $column = \Topi\Data\functions::columnStr($child->column, $params);
                $tv = null;

                switch ($child->type) {
                case 'startWith':
                    $tv = "{$child->value}%";
                    break;
                case 'endWith':
                    $tv = "%{$child->value}";
                    break;
                case 'contains':
                    $tv = "%{$child->value}%";
                    break;
                default:
                    throw new \Exception(sprintf("Unsupported type of %s %s", $child->type, gettype($child->type)));
                }

                $s = $this->uid();
                $command->param($s, $tv);

                $sql .= "{$column} like :{$s} {$brakets->operator} ";

                break;
            case 'like':
                if (!is_string($child->value)) {
                    throw new \Exception(sprintf("Unsupported type of % value %s", $child->type, gettype($child->value)));
                }

                $column = \Topi\Data\functions::columnStr($child->column, $params);

                $s = $this->uid();
                $command->param($s, $child->value);

                $sql .= "{$column} like :{$s} {$brakets->operator} ";

                break;
            case 'notLike':
                if (!is_string($child->value)) {
                    throw new \Exception(sprintf("Unsupported type of % value %s", $child->type, gettype($child->value)));
                }

                $column = \Topi\Data\functions::columnStr($child->column, $params);

                $s = $this->uid();
                $command->param($s, $child->value);

                $sql .= "{$column} not like :{$s} {$brakets->operator} ";

                break;
            case 'eq':
            case 'neq':
            case 'lt':
            case 'lte':
            case 'gt':
            case 'gte':
                $s = "";
                $column = \Topi\Data\functions::columnStr($child->column, $params);

                if (is_numeric($child->value)) {
                    $s = $child->value;
                }elseif(is_string($child->value)){
                    $uid = $this->uid();
                    $command->param($uid, $child->value);

                    $s = ":{$uid}";
                // }elseif($child->value instanceof \Topi\Data\Statements\Statement){
                //     $s = "({$child->value->slq()})";
                //     $command->merge($child->value->binds());
                // }elseif($child->value instanceof \Topi\Data\Adapters\Commands\SQL\Select){
                //     $t = $child->value->build();
                //     $s = "({$t->slq()})";
                //     $command->merge($t->binds());
                }else{
                    die(print_r($child, 1));
                    throw new \Exception(sprintf("Unsupported type of in value %s", gettype($child->value)));
                }

                $o = null;

                switch ($child->type) {
                case 'eq':
                    $o = "=";
                    break;
                case 'neq':
                    $o = "!=";
                    break;
                case 'lt':
                    $o = "<";
                    break;
                case 'lte':
                    $o = "<=";
                    break;
                case 'gt':
                    $o = ">";
                    break;
                case 'gte':
                    $o = ">=";
                    break;
                default:
                    throw new \Exception(sprintf("Unsupported type of operator %s", $child->type));
                }

                $sql .= "{$column} {$o} {$s} {$brakets->operator} ";

                break;
            case 'expr':
                $sql .= "{$child->expr} {$brakets->operator} ";
                break;
            case 'exists':
            case 'notExists':
                $s = "";
                $column = \Topi\Data\functions::columnStr($child->column, $params);

                if (is_string($child->value)) {
                    $s = $child->value;
                // }elseif($child->value instanceof \Topi\Data\Statements\Statement){
                //     $s = "({$child->value->slq()})";
                //     $command->merge($child->value->binds());
                // }elseif($child->value instanceof \Topi\Data\Adapters\Commands\SQL\Select){
                //     $t = $child->value->build();
                //     $s = "({$t->slq()})";
                //     $command->merge($t->binds());
                }else{
                    throw new \Exception(sprintf("Unsupported type of in value %s", gettype($child->value)));
                }

                $o = 'exists';

                switch ($child->type) {
                case 'notExists':
                    $o = 'not exists';
                default:
                    throw new \Exception(sprintf("Unsupported type of operator %s", $child->type));
                }

                $sql .= "{$column} {$o}({$s}) {$brakets->operator} ";

                break;
            case 'between':
                $begin = $this->convForBetween($child->begin, $command);
                $end = $this->convForBetween($child->end, $command);

                $column = \Topi\Data\functions::columnStr($child->column, $params);

                $sql .= "{$column} between {$begin} and {$end} {$brakets->operator} ";

                break;
            default:
                throw new \Exception(sprintf("Unsupported type of where %s", $child->type));
            }
        }

        $sql = substr($sql, 0, strlen($sql) - strlen($brakets->operator) - 2);

        return $sql;
    }

    private function convForBetween($value, $command)
    {
        if (is_string($value)) {
            $t = $this->uid();
            $command->param($t, $value);
            $value = ":{$t}";
        }elseif(is_numeric($value)){
            $value = $t;
        // }elseif($value instanceof \Topi\Data\Statements\Statement){
        //     $value = "({$value->slq()})";
        //     $command->merge($value->binds());
        // }elseif($value instanceof \Topi\Data\Adapters\Commands\SQL\Select){
        //     $t = $value->build();
        //     $s = "({$t->slq()})";
        //     $command->merge($t->binds());
        }else{
            throw new \Exception(sprintf("Unsupported type of in value %s", gettype($value)));
        }

        return $value;
    }

    public function clean()
    {
        $this->where = new \stdClass();

        $this->where->type = 'brakets';
        $this->where->operator = 'and';
        $this->where->childs = array();

        $this->pw = $this->where;

        return $this;
    }
}
