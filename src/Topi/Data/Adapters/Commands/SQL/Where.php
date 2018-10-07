<?php
namespace Topi\Data\Adapters\Commands\SQL;

use Topi\Data\Adapters\Commands\Command;
use Topi\Data\Adapters\Commands\BuiltCommand;

class Where extends Command
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

        $operations = array(
            'not-in' => 'notIn',
            'NotIn' => 'notIn',
            'in' => 'in',
            'In' => 'in',
            'is-not-null' => 'isNotNull',
            'IsNotNull' => 'isNotNull',
            'is-null' => 'isNull',
            'IsNull' => 'isNull',
            'start-with' => 'startWith',
            'StartWith' => 'startWith',
            'end-with' => 'endWith',
            'EndWith' => 'endWith',
            'contains' => 'contains',
            'Contains' => 'contains',
            'not-like' => 'notLike',
            'NotLike' => 'notLike',
            'like' => 'like',
            'Like' => 'like',
            'not-equal' => 'notEqual',
            'NotEqual' => 'notEqual',
            'equal' => 'equal',
            'Equal' => 'equal',
            'lower-than-equal' => 'lowerThanEqual',
            'LowerThanEqual' => 'lowerThanEqual',
            'lower-than' => 'lowerThan',
            'LowerThan' => 'lowerThan',
            'greater-than-equal' => 'greaterThanEqual',
            'GreaterThanEqual' => 'greaterThanEqual',
            'greater-than' => 'greaterThan',
            'GreaterThan' => 'greaterThan',
            'between' => 'between',
            'Between' => 'between',
        );

        foreach ($values as $key => $value) {
            preg_match_all($re2, $key, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                preg_match_all($re, $key, $matches, PREG_SET_ORDER, 0);
            }

            if (empty($matches)) {
                $field = $this->unifiedColumn($key);

                if (array_key_exists($field, $fields)) {
                    if (is_array($value)) {
                        $this->in($field, $value);
                    } else {
                        $this->eq($field, $value);
                    }

                    continue;
                } else {
                    continue;
                }
            }

            $field = $this->unifiedColumn($matches[0][1]);
            $operation = $operations[$matches[0][2]];

            if (!array_key_exists($field, $fields)) {
                continue;
            }

            $allowedOperations = array();
            $explodedOperations = array();
            $fieldValue = $field;

            if (is_string($fields[$field])) {
                if ($fields[$field] == 'all') {
                    $allowedOperations = array(
                        'notIn',
                        'in',
                        'isNotNull',
                        'isNull',
                        'startWith',
                        'endWith',
                        'contains',
                        'notLike',
                        'like',
                        'notEqual',
                        'equal',
                        'lowerThanEqual',
                        'lowerThan',
                        'greaterThanEqual',
                        'greaterThan',
                        'between',
                    );
                } else {
                    $allowedOperations = array($fields[$field]);
                }
            } elseif (is_array($fields[$field])) {
                if (array_key_exists('field', $fields[$field])) {
                    $fieldValue = $fields[$field]['field'];
                }

                if (array_key_exists('operations', $fields[$field])) {
                    $allowedOperations = $fields[$field]['operations'];
                } else {
                    $allowedOperations = array(
                        'notIn',
                        'in',
                        'isNotNull',
                        'isNull',
                        'startWith',
                        'endWith',
                        'contains',
                        'notLike',
                        'like',
                        'notEqual',
                        'equal',
                        'lowerThanEqual',
                        'lowerThan',
                        'greaterThanEqual',
                        'greaterThan',
                        'between',
                    );
                }

                if (array_key_exists('excluded', $fields[$field])) {
                    $explodedOperations = $fields[$field]['excluded'];
                }
            }

            if (in_array($operation, $explodedOperations)) {
                continue;
            }

            if (!in_array($operation, $allowedOperations)) {
                continue;
            }

            switch($operation) {
            case 'notIn':
                $this->notIn($fieldValue, $value);
                break;
            case 'in':
                $this->in($fieldValue, $value);
                break;
            case 'isNotNull':
                $this->isNotNull($fieldValue);
                break;
            case 'isNull':
                $this->isNull($fieldValue);
                break;
            case 'startWith':
                $this->startWith($fieldValue, $value);
                break;
            case 'endWith':
                $this->endWith($fieldValue, $value);
                break;
            case 'contains':
                $this->contains($fieldValue, $value);
                break;
            case 'notLike':
                $this->notLike($fieldValue, $value);
                break;
            case 'like':
                $this->like($fieldValue, $value);
                break;
            case 'notEqual':
                $this->neq($fieldValue, $value);
                break;
            case 'equal':
                $this->eq($fieldValue, $value);
                break;
            case 'lowerThanEqual':
                $this->lte($fieldValue, $value);
                break;
            case 'lowerThan':
                $this->lt($fieldValue, $value);
                break;
            case 'greaterThanEqual':
                $this->gte($fieldValue, $value);
                break;
            case 'greaterThan':
                $this->gt($fieldValue, $value);
                break;
            case 'between':
                if (is_string($value)) {
                    $value = explode(',', $value);

                    $this->between($fieldValue, $value[0], $value[1]);
                } else {
                    if (array_key_exists('from', $value) && array_key_exists('to', $value)) {
                        $this->between($fieldValue, $value['from'], $value['to']);
                    }
                }

                break;
            }
        }

        return $this;
    }

    private function unifiedColumn(string $name)
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

    /**
     * Method allow add condition base on type of value. For example if
     * conditions is array then 'in()' statement is added. Below are some
     * example.
     *
     * array(1,2) - in(1,2)
     * 1 - in(1)
     *
     * array(
     *     'from' => 1, - 'between 1 and 4'
     *     'to' => 4,
     * )
     *
     * Default operator is 'in' you can change this with param 'operator'.
     * Operator can be in, contains.
     *
     * @param string $column
     * @param mixed $conditions
     * @param array $params
     *     - operator
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
            if (array_key_exists('from', $conditions) || array_key_exists('to', $conditions)) {
                if (array_key_exists('from', $conditions) && array_key_exists('to', $conditions)) {
                    $this->between($column, $conditions['from'], $conditions['to']);
                } elseif (array_key_exists('from', $conditions)) {
                    $this->gte($column, $conditions['from']);
                } elseif (array_key_exists('to', $conditions)) {
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
        // case 'equal':
        //     // Robimy nawias ze wszystkimi ORAMI
        //     $this->brackets(function($where) use ($value, $column){
        //         $where->oro();

        //         foreach ($value as $v) {
        //             $where->eq($column, $v);
        //         }
        //     });

        //     break;
        default:
            throw new \InvalidArgumentException("Unsupported operator {$param['operator']}.");
        }

        return $this;
    }

    /**
     * Add in() statement to where. There are few type of methods to call.
     *
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->in('u.id', array(11, 12, 13, 14, 15))
     *     ->order('id asc')
     *     ->fetch()
     * ;
     *
     * // There is no need to check if array has values. If not 1=2 is place
     * // in place of in()
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->in('u.id', array())
     *     ->order('id asc')
     *     ->fetch()
     * ;
     *
     * // Other sub query can be use.
     * $sub = (new Select())
     *     ->from('users')
     *     ->column('id')
     *     ->in('u.id', array(11, 12, 13, 14, 15, 'test'))
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->in('u.id', $sub)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     * ```
     */

    public function in($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'in';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    /**
     * Add 'not in()' statement to where. It works like 'in()' but add 'not'.
     *
     * ```php
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->notIn('u.id', array(1, 2, 3, 4, 5))
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     *
     * // Other sub query can be use.
     * $sub = (new Select())
     *     ->from('users')
     *     ->column('id')
     *     ->in('u.id', array(1, 2, 3, 4, 5, 'test'))
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->notIn('u.id', $sub)
     *     ->limit(5)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     * ```
     *
     */
    public function notIn($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'notIn';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    /**
     * Add 'is null' statement to where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->column('u.countryId')
     *     ->isNull('u.countryId')
     *     ->order('id asc')
     *     ->limit(10)
     *     ->fetch()
     * ;
     * ```
     */
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

    /**
     * Add like "%value" to where statement.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.countryId')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->isNotNull('u.countryId')
     *     ->startWith('u.firstName', 'Ali')
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.countryId')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->isNotNull('u.countryId')
     *     ->startWith('u.firstName', new Expr('"Aliz"'))
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     * ```
     *
     */
    public function startWith($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'startWith';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    /**
     * Add %value end with statement to where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->endWith('u.firstName', 'trée')
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->endWith('u.firstName', new Expr('"trée"'))
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     * ```
     */
    public function endWith($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'endWith';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    /**
     * Add 'like %value%' statement too where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->contains('u.firstName', 'ébec')
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->contains('u.firstName', new Expr('"ébec"'))
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     * ```
     *
     */
    public function contains($column, $value)
    {
        $child = new \stdClass();
        $child->type = 'contains';
        $child->column = $column;
        $child->value = $value;

        $this->pw->childs[] = $child;

        return $this;
    }

    /**
     * Add 'like value' statement to where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->contains('u.firstName', 'No%l%a')
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->contains('u.firstName', new Expr('"No%l%a"'))
     *     ->order('id asc')
     *     ->limit(2)
     *     ->fetch()
     * ;
     * ```
     */
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
        $child->value = $expr;

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
        $buildCommand = new BuiltCommand();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        if (count($this->pw->childs) > 0) {
            $buildCommand->command("where {$this->buildBrakets($this->pw, $buildCommand, $params)}");

            return $buildCommand;
        }else{
            return $buildCommand;
        }
    }

    private function buildBrakets(\stdClass $brakets, BuiltCommand $buildCommand, array $params)
    {
        // init sql for braket
        $sql = "";

        foreach ($brakets->childs as $child) {
            $childValue = null;

            if ($child->type == 'brakets') {
                $sql .= "({$this->buildBrakets($child, $buildCommand, $params)}) {$brakets->operator} ";
            } elseif ($child->type == 'expr') {
                $sql .= sprintf("%s {$brakets->operator} ", (string) $child->value);
            } elseif ($child->type == 'exists' || $child->type == 'notExists') {
                $s = "";
                $not = "";

                if ($child->type == 'notExists') {
                    $not = 'not ';
                }

                if ($child->value instanceof Command) {
                    $valueBuiltCommand = $child->value->build($params);
                    $buildCommand->params($valueBuiltCommand->params());

                    $s = "({$valueBuiltCommand->command()})";
                } else {
                    throw new \InvalidArgumentException(sprintf("Unsported type of value %s for {$child->type}.", gettype($child->value)));
                }

                $sql .= "{$not}exists {$s} {$brakets->operator} ";
            } else {

                // Parse column
                $column = \Topi\Data\functions::columnStr($child->column, $params);

                switch ($child->type) {
                // in, not in
                case 'in':
                case 'notIn':
                    $s = "";
                    $not = "";

                    if ($child->type == 'notIn') {
                        $not = 'not ';
                    }

                    if (is_string($child->value)) {
                        $buildCommand->param($uid = $this->uid(), $child->value);
                        $s = ":{$uid}";

                    } else if (is_numeric($child->value)) {
                        $s = "{$child->value}";
                    } else if (is_array($child->value)) {
                        foreach ($child->value as $value) {
                            if (is_numeric($value)) {
                                $s .= "{$value},";
                            }else{
                                $buildCommand->param($uid = $this->uid(), $value);
                                $s .= ":{$uid},";
                            }
                        }

                        $s = substr($s, 0, strlen($s) - 1);
                    } else if ($child->value instanceof Command) {
                        $valueBuiltCommand = $child->value->build($params);
                        $buildCommand->params($valueBuiltCommand->params());

                        $s = "({$valueBuiltCommand->command()})";
                    } else {
                        throw new \InvalidArgumentException(sprintf("Unsupported type of value %s for in at where.", gettype($child->value)));
                    }

                    if (empty($s)) {
                        if ($child->type == 'in') {
                            $sql .= "(1=2) {$brakets->operator} ";
                        } else {
                            $sql .= "(1=1) {$brakets->operator} ";
                        }
                    }else{
                        $sql .= "{$column} {$not}in({$s}) {$brakets->operator} ";
                    }

                    break;
                case 'isNull':
                case 'isNotNull':
                    $s = "";
                    $not = "";

                    if ($child->type == 'isNotNull') {
                        $not = 'not ';
                    }

                    $o = $child->type == 'isNotNull' ? 'is not null' : 'is null';

                    $sql .= "{$column} is {$not}null {$brakets->operator} ";

                    break;
                case 'startWith':
                case 'endWith':
                case 'contains':
                    $s = "";

                    if ($child->value instanceof Command) {
                        $valueBuiltCommand = $child->value->build($params);
                        $buildCommand->params($valueBuiltCommand->params());

                        if ($child->type == 'startWith') {
                            $s .= "concat(({$valueBuiltCommand->command()}), '%')";
                        } else if($child->type == 'endWith') {
                            $s .= "concat('%', ({$valueBuiltCommand->command()}))";
                        } else if($child->type == 'contains') {
                            $s .= "concat('%', ({$valueBuiltCommand->command()}), '%')";
                        } else {
                            throw new \InvalidArgumentException("Unsupported type {$child->type}.");
                        }

                        $sql .= "{$column} like {$s} {$brakets->operator} ";
                    } else {
                        if (
                               !is_string($child->value)
                            && !is_numeric($child->value)
                        ) {
                            throw new \InvalidArgumentException(sprintf("Unsported type of value %s", gettype($child->value)));
                        }

                        if ($child->type == 'startWith') {
                            $s = "{$child->value}%";
                        } else if($child->type == 'endWith') {
                            $s = "%{$child->value}";
                        } else if($child->type == 'contains') {
                            $s = "%{$child->value}%";
                        } else {
                            throw new \InvalidArgumentException("Unsupported type {$child->type}.");
                        }

                        $buildCommand->param($uid = $this->uid(), $s);

                        $sql .= "{$column} like :{$uid} {$brakets->operator} ";
                    }

                    break;
                case 'like':
                case 'notLike':
                    $s = "";
                    $not = "";

                    if ($child->type == 'notLike') {
                        $not = 'not ';
                    }

                    if ($child->value instanceof Command) {
                        $valueBuiltCommand = $child->value->build($params);
                        $buildCommand->params($valueBuiltCommand->params());

                        if ($child->type == 'startWith') {
                            $s .= "concat(({$valueBuiltCommand->command()}), '%')";
                        } else if($child->type == 'endWith') {
                            $s .= "concat('%', ({$valueBuiltCommand->command()}))";
                        } else if($child->type == 'contains') {
                            $s .= "concat('%', ({$valueBuiltCommand->command()}), '%')";
                        } else {
                            throw new \InvalidArgumentException("Unsupported type {$child->type}.");
                        }

                        $sql .= "{$column} {$not}like {$s} {$brakets->operator} ";
                    } else {
                        if (
                               !is_string($child->value)
                            && !is_numeric($child->value)
                        ) {
                            throw new \InvalidArgumentException(sprintf("Unsported type of value %s", gettype($child->value)));
                        }

                        if ($child->type == 'startWith') {
                            $s = "{$child->value}%";
                        } else if($child->type == 'endWith') {
                            $s = "%{$child->value}";
                        } else if($child->type == 'contains') {
                            $s = "%{$child->value}%";
                        } else {
                            throw new \InvalidArgumentException("Unsupported type {$child->type}.");
                        }

                        $buildCommand->param($uid = $this->uid(), $s);

                        $sql .= "{$column} {$not}like :{$uid} {$brakets->operator} ";
                    }

                    break;
                case 'eq':
                case 'neq':
                case 'lt':
                case 'lte':
                case 'gt':
                case 'gte':
                    $s = "";

                    if (is_string($child->value)) {
                        $buildCommand->param($uid = $this->uid(), $child->value);
                        $s = ":{$uid}";
                    } elseif (is_numeric($child->value)) {
                        $s = "{$child->value}";
                    } elseif ($child->value instanceof Command) {
                        $valueBuiltCommand = $child->value->build($params);
                        $buildCommand->params($valueBuiltCommand->params());

                        $s = "({$valueBuiltCommand->command()})";
                    } else {
                        throw new \InvalidArgumentException(sprintf("Unsported type of value %s", gettype($child->value)));
                    }

                    $o = null;

                    if ($child->type == 'eq') {
                        $o = "=";
                    } elseif ($child->type == 'neq') {
                        $o = "!=";
                    } elseif ($child->type == 'lt') {
                        $o = "<";
                    } elseif ($child->type == 'lte') {
                        $o = "<=";
                    } elseif ($child->type == 'gt') {
                        $o = ">";
                    } elseif ($child->type == 'gte') {
                        $o = ">=";
                    } else {
                        throw new \Exception(sprintf("Unsupported type of operator %s", $child->type));
                    }

                    $sql .= "{$column} {$o} {$s} {$brakets->operator} ";

                    break;
                // case 'expr':
                //     $sql .= "{$child->expr} {$brakets->operator} ";

                    break;
                case 'between':
                    $begin = $this->convForBetween($child->begin, $buildCommand);
                    $end = $this->convForBetween($child->end, $buildCommand);

                    $sql .= "{$column} between {$begin} and {$end} {$brakets->operator} ";

                    break;
                default:
                    throw new \Exception(sprintf("Unsupported type of where %s", $child->type));
                }
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
            // $value = $t;
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
