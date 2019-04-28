<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\Commands\Built;
use Tiie\Utils\Functions;

use stdClass;

class Where extends Command
{
    /**
     * @var stdClass
     */
    private $where;

    /**
     * @var stdClass
     */
    private $pointer;

    /**
     * @var Common
     */
    private $common;

    function __construct()
    {
        parent::__construct();

        $this->where = new stdClass();

        $this->where->type = 'brackets';
        $this->where->operator = 'AND';
        $this->where->childs = array();

        $this->common = new Common();

        $this->pointer = $this->where;
    }

    public function brackets($function, $scope = null)
    {
        $child = new stdClass();
        $child->type = 'brackets';
        $child->operator = 'AND';
        $child->childs = array();

        if (is_null($scope)) {
            $scope = $this;
        }

        $this->pointer->childs[] = $child;

        // Zapisuje pointer
        $pwt = $this->pointer;
        $this->pointer = $child;

        call_user_func_array($function, array($scope));

        // Cofam pointer do poprzedniego stanu
        $this->pointer = $pwt;

        return $this;
    }

    public function and(array $conditions = null)
    {
        if (is_null($conditions)) {
            $this->pointer->operator = 'AND';
        } else {
            $child = new stdClass();

            $child->type = 'and';
            $child->column = null;
            $child->value = $conditions;

            $this->pointer->childs[] = $child;
        }

        return $this;
    }

    public function or(array $conditions = null)
    {
        if (is_null($conditions)) {
            $this->pointer->operator = 'OR';
        } else {
            $child = new stdClass();

            $child->type = 'or';
            $child->column = null;
            $child->value = $conditions;

            $this->pointer->childs[] = $child;
        }

        return $this;
    }

    public function params(array $values = array(), array $fields = array())
    {
        $re = '/^(.*?)(-in|In|-not-in|NotIn|-is-null|IsNull|-is-not-null|IsNotNull|-start-with|StartWith|-not-start-with|NotStartWith|-end-with|EndWith|-not-end-with|NotEndWith|-contains|Contains|-not-contains|NotContains|-like|Like|-not-like|NotLike|-equal|Equal|-not-equal|NotEqual|-lower-than-equal|LowerThanEqual|-not-lower-than-equal|NotLowerThanEqual|-lower-than|LowerThan|-not-lower-than|NotLowerThan|-greater-than-equal|GreaterThanEqual|-not-greater-than-equal|NotGreaterThanEqual|-greater-than|GreaterThan|-not-greater-than|NotGreaterThan|-between|Between|-not-between|NotBetween)$/m';

        $columns = array();

        $operations = array(
            '-in' => 'in',
            'In' => 'in',
            '-not-in' => 'notIn',
            'NotIn' => 'notIn',

            '-is-null' => 'isNull',
            'IsNull' => 'isNull',
            '-is-not-null' => 'isNotNull',
            'IsNotNull' => 'isNotNull',

            '-start-with' => 'startWith',
            'StartWith' => 'startWith',
            '-not-start-with' => 'notStartWith',
            'NotStartWith' => 'notStartWith',

            '-end-with' => 'endWith',
            'EndWith' => 'endWith',
            '-not-end-with' => 'notEndWith',
            'NotEndWith' => 'notEndWith',

            '-contains' => 'contains',
            'Contains' => 'contains',
            '-not-contains' => 'notContains',
            'NotContains' => 'notContains',

            '-like' => 'like',
            'Like' => 'like',
            '-not-like' => 'notLike',
            'NotLike' => 'notLike',

            '-equal' => 'equal',
            'Equal' => 'equal',
            '-not-equal' => 'notEqual',
            'NotEqual' => 'notEqual',

            '-lower-than-equal' => 'lowerThanEqual',
            'LowerThanEqual' => 'lowerThanEqual',
            '-not-lower-than-equal' => 'notLowerThanEqual',
            'NotLowerThanEqual' => 'notLowerThanEqual',

            '-lower-than' => 'lowerThan',
            'LowerThan' => 'lowerThan',
            '-not-lower-than' => 'notLowerThan',
            'NotLowerThan' => 'notLowerThan',

            '-greater-than-equal' => 'greaterThanEqual',
            'GreaterThanEqual' => 'greaterThanEqual',
            '-not-greater-than-equal' => 'notGreaterThanEqual',
            'NotGreaterThanEqual' => 'notGreaterThanEqual',

            '-greater-than' => 'greaterThan',
            'GreaterThan' => 'greaterThan',
            '-not-greater-than' => 'notGreaterThan',
            'NotGreaterThan' => 'notGreaterThan',

            '-between' => 'between',
            'Between' => 'between',
            '-not-between' => 'notBetween',
            'NotBetween' => 'notBetween',
        );

        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                unset($fields[$key]);
                $fields[$value] = array();
            }
        }

        foreach ($values as $key => $value) {
            preg_match_all($re, $key, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                $field = $this->unifiedColumn($key);

                // standard field
                if (!empty($fields)) {
                    if (array_key_exists($field, $fields)) {
                        if (array_key_exists($field, $fields) && is_array($fields[$field]) && array_key_exists('field', $fields[$field])) {
                            $field = $fields[$field]['field'];
                        }

                        if (is_array($value)) {
                            if (array_key_exists("from", $value) && array_key_exists("to", $value)) {
                                $this->greaterThanEqual($field, $value["from"]);
                                $this->lowerThanEqual($field, $value["to"]);
                            } else if (array_key_exists("from", $value)) {
                                $this->greaterThanEqual($field, $value["from"]);
                            } elseif (array_key_exists("to", $value)) {
                                $this->lowerThanEqual($field, $value["to"]);
                            } else {
                                $this->in($field, $value);
                            }
                        } else {
                            $this->equal($field, $value);
                        }

                        continue;
                    } else {
                        continue;
                    }
                } else {
                    if (is_array($value)) {
                        if (array_key_exists("from", $value) && array_key_exists("to", $value)) {
                            $this->greaterThanEqual($field, $value["from"]);
                            $this->lowerThanEqual($field, $value["to"]);
                        } else if (array_key_exists("from", $value)) {
                            $this->greaterThanEqual($field, $value["from"]);
                        } elseif (array_key_exists("to", $value)) {
                            $this->lowerThanEqual($field, $value["to"]);
                        } else {
                            $this->in($field, $value);
                        }
                    } else {
                        $this->equal($field, $value);
                    }

                    continue;
                }
            }

            $field = $this->unifiedColumn($matches[0][1]);
            $operation = $operations[$matches[0][2]];

            if (!empty($fields)) {
                if (!array_key_exists($field, $fields)) {
                    continue;
                }
            }

            $operationsAllowed = array(
                'in',
                'notIn',
                'isNull',
                'isNotNull',
                'startWith',
                'notStartWith',
                'endWith',
                'notEndWith',
                'contains',
                'notContains',
                'like',
                'notLike',
                'equal',
                'notEqual',
                'lowerThanEqual',
                'notLowerThanEqual',
                'lowerThan',
                'notLowerThan',
                'greaterThanEqual',
                'notGreaterThanEqual',
                'greaterThan',
                'notGreaterThan',
                'between',
                'notBetween',
            );

            $operationsExcluded = array();
            $fieldValue = $field;

            if (array_key_exists($field, $fields) && is_array($fields[$field])) {
                if (array_key_exists('field', $fields[$field])) {
                    $fieldValue = $fields[$field]['field'];
                }

                if (array_key_exists('operations', $fields[$field])) {
                    $operationsAllowed = $fields[$field]['operations'];
                }

                if (array_key_exists('excluded', $fields[$field])) {
                    $operationsExcluded = $fields[$field]['excluded'];
                }
            }

            if (in_array($operation, $operationsExcluded)) {
                continue;
            }

            if (!in_array($operation, $operationsAllowed)) {
                continue;
            }

            switch($operation) {
            case 'in':
                $this->in($fieldValue, $value);
                break;
            case 'notIn':
                $this->notIn($fieldValue, $value);
                break;
            case 'isNull':
                $this->isNull($fieldValue);
                break;
            case 'isNotNull':
                $this->isNotNull($fieldValue);
                break;
            case 'startWith':
                $this->startWith($fieldValue, $value);
                break;
            case 'notStartWith':
                $this->notStartWith($fieldValue, $value);
                break;
            case 'endWith':
                $this->endWith($fieldValue, $value);
                break;
            case 'notEndWith':
                $this->notEndWith($fieldValue, $value);
                break;
            case 'contains':
                $this->contains($fieldValue, $value);
                break;
            case 'notContains':
                $this->notContains($fieldValue, $value);
                break;
            case 'like':
                $this->like($fieldValue, $value);
                break;
            case 'notLike':
                $this->notLike($fieldValue, $value);
                break;
            case 'equal':
                $this->equal($fieldValue, $value);
                break;
            case 'notEqual':
                $this->notEqual($fieldValue, $value);
                break;
            case 'lowerThanEqual':
                $this->lowerThanEqual($fieldValue, $value);
                break;
            case 'notLowerThanEqual':
                $this->notLowerThanEqual($fieldValue, $value);
                break;
            case 'lowerThan':
                $this->lowerThan($fieldValue, $value);
                break;
            case 'notLowerThan':
                $this->notLowerThan($fieldValue, $value);
                break;
            case 'greaterThanEqual':
                $this->greaterThanEqual($fieldValue, $value);
                break;
            case 'notGreaterThanEqual':
                $this->notGreaterThanEqual($fieldValue, $value);
                break;
            case 'greaterThan':
                $this->greaterThan($fieldValue, $value);
                break;
            case 'notGreaterThan':
                $this->notGreaterThan($fieldValue, $value);
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
            case 'notBetween':
                if (is_string($value)) {
                    $value = explode(',', $value);

                    $this->notBetween($fieldValue, $value[0], $value[1]);
                } else {
                    if (array_key_exists('from', $value) && array_key_exists('to', $value)) {
                        $this->notBetween($fieldValue, $value['from'], $value['to']);
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
                    $this->greaterThanEqual($column, $conditions['from']);
                } elseif (array_key_exists('to', $conditions)) {
                    $this->lowerThanEqual($column, $conditions['to']);
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
                $where->or();

                foreach ($value as $v) {
                    $where->contains($column, $v);
                }
            });

            break;
        // case 'equal':
        //     // Robimy nawias ze wszystkimi ORAMI
        //     $this->brackets(function($where) use ($value, $column){
        //         $where->or();

        //         foreach ($value as $v) {
        //             $where->equal($column, $v);
        //         }
        //     });

        //     break;
        default:
            throw new \InvalidArgumentException("Unsupported operator {$params['operator']}.");
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
        $child = new stdClass();

        $child->type = 'in';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'notIn';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'isNull';
        $child->column = $column;
        $child->value = null;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function isNotNull($column)
    {
        $child = new stdClass();

        $child->type = 'isNotNull';
        $child->column = $column;
        $child->value = null;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'startWith';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notStartWith($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notStartWith';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'endWith';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notEndWith($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notEndWith';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'contains';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notContains($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notContains';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

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
        $child = new stdClass();

        $child->type = 'like';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notLike($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notLike';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function equal($column, $value)
    {
        $child = new stdClass();

        $child->type = 'equal';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notEqual($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notEqual';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function lowerThan($column, $value)
    {
        $child = new stdClass();

        $child->type = 'lowerThan';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notLowerThan($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notLowerThan';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function lowerThanEqual($column, $value)
    {
        $child = new stdClass();

        $child->type = 'lowerThanEqual';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notLowerThanEqual($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notLowerThanEqual';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function greaterThan($column, $value)
    {
        $child = new stdClass();

        $child->type = 'greaterThan';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notGreaterThan($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notGreaterThan';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function greaterThanEqual($column, $value)
    {
        $child = new stdClass();

        $child->type = 'greaterThanEqual';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notGreaterThanEqual($column, $value)
    {
        $child = new stdClass();

        $child->type = 'notGreaterThanEqual';
        $child->column = $column;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function expr($expr)
    {
        $child = new stdClass();
        $child->type = 'expr';
        $child->column = null;
        $child->value = $expr;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function exists($value)
    {
        $child = new stdClass();
        $child->type = 'exists';
        $child->column = null;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notExists($value)
    {
        $child = new stdClass();
        $child->type = 'notExists';
        $child->column = null;
        $child->value = $value;

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function between($column, $begin, $end)
    {
        $child = new stdClass();
        $child->type = 'between';
        $child->column = $column;
        $child->value = array($begin, $end);

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function notBetween($column, $begin, $end)
    {
        $child = new stdClass();
        $child->type = 'notBetween';
        $child->column = $column;
        $child->value = array($begin, $end);

        $this->pointer->childs[] = $child;

        return $this;
    }

    public function build(array $params = array()) : Built
    {
        $built = new Built();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        if (count($this->pointer->childs) > 0) {
            $built->command($this->buildBrakets($this->pointer, $built, $params));

            return $built;
        }else{
            return $built;
        }
    }

    private function buildBrakets(stdClass $brackets, Built $built, array $params = array())
    {
        $command = "";
        $params = array();
        $t = null;
        $bl = null;

        foreach ($brackets->childs as $condition) {
            if ($condition->type == "brackets") {
                $command .= "({$this->buildBrakets($condition, $built, $params)}) {$brackets->operator} ";

                continue;
            }

            $type = $condition->type;
            $column = $condition->column;
            $value = $condition->value;

            // Column
            if(
                $column instanceof Select ||
                $column instanceof Expr ||
                $column instanceof Where
            ) {
                $bl = $column->build();

                // $params = array_merge($params, $bl->params());
                $built->params($bl->params());
                $column = sprintf("(%s)", $bl->command());
            } else if(is_string($column)) {
                $t = $this->common->resolveColumn($column);

                if (is_null($t)) {
                    trigger_error("I can't resolve column '{$column}'.", E_USER_WARNING);
                    continue;
                } else {
                    $column = $t;
                }

                if (!is_null($column["table"])) {
                    $column = "`{$column["table"]}`.`{$column["column"]}`";
                } else {
                    $column = "`{$column["column"]}`";
                }
            } else if(is_null($column) && in_array($type, array(
                "exists",
                "notExists",
                "expr",
                "and",
                "or",
            ))) {
                // Ommit null. It is allowed for these types.
            } else {
                trigger_error(sprintf("Unsupported type of column '%s'.", Functions::type($column)), E_USER_WARNING);

                continue;
            }

            // Value
            if(
                $value instanceof Select ||
                $value instanceof Expr ||
                $value instanceof Where
            ) {
                $bl = $value->build();

                $built->params($bl->params());
                $value = sprintf("(%s)", $bl->command());
            } else if(is_string($value) && in_array($type, array("expr"))) {
                // Put where directly.
            } else if(is_string($value) && in_array($type, array("startWith", "notStartWith"))) {
                $built->param($t = $this->uid(), "{$value}%");
                $value = ":{$t}";
            } else if(is_string($value) && in_array($type, array("endWith", "notEndWith"))) {
                $built->param($t = $this->uid(), "%{$value}");
                $value = ":{$t}";
            } else if(is_string($value) && in_array($type, array("contains", "notContains"))) {
                $built->param($t = $this->uid(), "%{$value}%");
                $value = ":{$t}";
            } else if(is_string($value)) {
                $built->param($t = $this->uid(), $value);
                $value = ":{$t}";
            } else if(is_numeric($value)) {
                // Omit numeric
            } else if(is_array($value) && in_array($type, array("in", "notIn"))) {
                $string = "";

                if (empty($value)) {
                    if ($type == "in") {
                        $value = "1=2";
                    } else {
                        $value = "1=1";
                    }
                } else {
                    foreach ($value as $v) {
                        if (is_numeric($v)) {
                            $string .= "{$v},";
                        } else {
                            $built->param($t = $this->uid(), $v);
                            $string .= ":{$t},";
                        }
                    }

                    $value = substr($string, 0, strlen($string) - 1);
                }
            } else if(is_array($value) && in_array($type, array("between", "notBetween"))) {
                $begin = $value[0];
                $end = $value[1];

                if (!is_numeric($value[0])) {
                    $built->param($t = $this->uid(), $value[0]);
                    $value[0] = ":{$t}";
                }

                if (!is_numeric($value[1])) {
                    $built->param($t = $this->uid(), $value[1]);
                    $value[1] = ":{$t}";
                }

            } else if(is_array($value) && in_array($type, array("and", "or"))) {
                // ...
                $string = "";
                $operator = $type == "and" ? "AND" : "OR";

                foreach ($value as $v) {
                    if(
                        $v instanceof Select ||
                        $v instanceof Expr ||
                        $v instanceof Where
                    ) {
                        $bl = $v->build();

                        $built->params($bl->params());
                        $string .= sprintf("(%s) {$operator} ", $bl->command());
                    } else if(is_string($v)) {
                        $string .= sprintf("(%s) {$operator} ", $v);
                    } else {
                        trigger_error(sprintf("Unssuported type of value for or/and method '%s'.", Functions::type($v)), E_USER_WARNING);
                    }

                    // $where->or(array($a, $b, $c));
                    // (... and (($a) or ($b) or ($c)))
                }

                $value = sprintf("(%s)", substr($string, 0, strlen($string) - strlen($operator) - 2));
            } else if(is_null($value) && in_array($type, array(
                "isNull",
                "isNotNull",
            ))) {
                // Ommit null. It is allowed for these types.
            } else {
                trigger_error(sprintf("Unsupported type of value '%s'.", Functions::type($value)), E_USER_WARNING);

                continue;
            }

            switch ($type) {
            // ---------------------
            case "or":
            case "and":
                $command .= "{$value}";
                break;

            // ---------------------
            case "expr":
                $command .= "{$value}";
                break;

            // ---------------------
            case "in":
                $command .= "{$column} IN({$value})";
                break;
            case "notIn":
                $command .= "{$column} NOT IN({$value})";
                break;

            // ---------------------
            case "isNull":
                $command .= "{$column} IS NULL";
                break;
            case "isNotNull":
                $command .= "{$column} IS NOT NULL";
                break;

            // ---------------------
            case "like":
            case "startWith":
            case "endWith":
            case "contains":
                $command .= "{$column} LIKE {$value}";
                break;
            case "notLike":
            case "notStartWith":
            case "notEndWith":
            case "notContains":
                $command .= "{$column} NOT like {$value}";
                break;

            // ---------------------
            case "equal":
                $command .= "{$column} = {$value}";
                break;
            case "notEqual":
                $command .= "{$column} != {$value}";
                break;

            // ---------------------
            case "lowerThan":
                $command .= "{$column} < {$value}";
                break;
            case "notLowerThan":
                $command .= "NOT ({$column} > {$value})";
                break;

            // ---------------------
            case "lowerThanEqual":
                $command .= "{$column} <= {$value}";
                break;
            case "notLowerThanEqual":
                $command .= "NOT ({$column} <= {$value})";
                break;

            // ---------------------
            case "greaterThan":
                $command .= "{$column} > {$value}";
                break;
            case "notGreaterThan":
                $command .= "NOT ({$column} > {$value})";
                break;

            // ---------------------
            case "greaterThanEqual":
                $command .= "{$column} >= {$value}";
                break;
            case "notGreaterThanEqual":
                $command .= "NOT ({$column} >= {$value})";
                break;

            // ---------------------
            case "exists":
                $command .= "{$column} EXISTS {$value}";
                break;
            case "notExists":
                $command .= "{$column} NOT EXISTS {$value}";
                break;

            // ---------------------
            case "between":
                $command .= "{$column} BETWEEN {$value[0]} AND {$value[1]}";
                break;
            case "notBetween":
                $command .= "{$column} NOT BETWEEN {$value[0]} AND {$value[1]}";
                break;
            }

            $command .= " {$brackets->operator} ";
        }

        $command = substr($command, 0, strlen($command) - strlen($brackets->operator) - 2);

        return $command;
    }

    public function clean()
    {
        $this->where = new stdClass();

        $this->where->type = 'brackets';
        $this->where->operator = 'and';
        $this->where->childs = array();

        $this->pointer = $this->where;

        return $this;
    }
}
