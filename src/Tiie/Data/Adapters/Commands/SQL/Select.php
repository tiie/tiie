<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\AdapterInterface;
use Tiie\Data\Adapters\Commands\Built;
use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\Commands\SQL\Common;
use Tiie\Data\Adapters\Commands\SQL\Where;
use Tiie\Data\Adapters\Commands\SQL\Expr;
use Tiie\Utils\Functions;

class Select extends Command
{
    const JOIN_LEFT = 1;
    const JOIN_RIGHT = 2;
    const JOIN_INNER = 3;
    const JOIN_OUTER = 4;

    protected $from = null;
    protected $columns = array();
    protected $joins = array();
    protected $order = array();
    protected $group = array();
    protected $limit = null;
    protected $having = null;
    protected $where = null;

    private $rules = array();

    /**
     * Podastawowa regula.
     */
    private $defaultRuleFun;

    function __construct(AdapterInterface $adapter = null)
    {
        parent::__construct($adapter);

        $this->where = new Where();
        $this->common = new Common();

        $this->defaultRuleFun = function($values, $select){
            foreach ($values as $name => $value) {
                $select->eq($name, $value);
            }

            return true;
        };
    }

    /**
     * Set default rule to process all params.
     *
     * @param callable
     * @return $this
     */
    public function defaultRule($defaultRule)
    {
        $this->defaultRuleFun = $defaultRule;

        return $this;
    }

    /**
     * Assign params to process by specific rule.
     *
     * @param array $params List of params to trigger rule.
     * @param callable $rule
     * @return $this
     */
    public function rule(array $params, callable $rule)
    {
        if (!is_array($params)) {
            $params = array($params);
        }

        $this->rules[] = array(
            'params' => $params,
            'rule' => $rule
        );

        return $this;
    }

    /**
     * Process params given params.
     *
     * @param array $params
     * @return $this
     */
    public function process(array $params = array())
    {
        $paramsKeys = array_keys($params);

        foreach ($this->rules as $rule) {
            $values = array();

            foreach ($rule['params'] as $param) {
                if (in_array($param, $paramsKeys)) {
                    $values[$param] = $params[$param];
                }else{
                    continue 2;
                }
            }

            if (!is_null($rule['rule'])) {
                call_user_func_array($rule['rule'], array($values, $this));
            }else{
                call_user_func_array($this->defaultRuleFun, array($values, $this));
            }
        }

        return $this;
    }

    public function in($column, $value)
    {
        $this->where->in($column, $value);

        return $this;
    }

    public function notIn($column, $value)
    {
        $this->where->notIn($column, $value);

        return $this;
    }

    public function isNull($column)
    {
        $this->where->isNull($column);

        return $this;
    }

    public function isNotNull($column)
    {
        $this->where->isNotNull($column);

        return $this;
    }

    public function startWith($column, $value)
    {
        $this->where->startWith($column, $value);

        return $this;
    }

    public function notStartWith($column, $value)
    {
        $this->where->notStartWith($column, $value);

        return $this;
    }

    public function endWith($column, $value)
    {
        $this->where->endWith($column, $value);

        return $this;
    }

    public function notEndWith($column, $value)
    {
        $this->where->notEndWith($column, $value);

        return $this;
    }

    public function contains($column, $value)
    {
        $this->where->contains($column, $value);

        return $this;
    }

    public function notContains($column, $value)
    {
        $this->where->notContains($column, $value);

        return $this;
    }

    public function like($column, $value)
    {
        $this->where->like($column, $value);

        return $this;
    }

    public function notLike($column, $value)
    {
        $this->where->notLike($column, $value);

        return $this;
    }

    public function equal($column, $value)
    {
        $this->where->equal($column, $value);

        return $this;
    }

    public function notEqual($column, $value)
    {
        $this->where->notEqual($column, $value);

        return $this;
    }

    public function lowerThan($column, $value)
    {
        $this->where->lowerThan($column, $value);

        return $this;
    }

    public function notLowerThan($column, $value)
    {
        $this->where->notLowerThan($column, $value);

        return $this;
    }

    public function lowerThanEqual($column, $value)
    {
        $this->where->lowerThanEqual($column, $value);

        return $this;
    }

    public function notLowerThanEqual($column, $value)
    {
        $this->where->notLowerThanEqual($column, $value);

        return $this;
    }

    public function greaterThan($column, $value)
    {
        $this->where->greaterThan($column, $value);

        return $this;
    }

    public function notGreaterThan($column, $value)
    {
        $this->where->notGreaterThan($column, $value);

        return $this;
    }

    public function greaterThanEqual($column, $value)
    {
        $this->where->greaterThanEqual($column, $value);

        return $this;
    }

    public function notGreaterThanEqual($column, $value)
    {
        $this->where->notGreaterThanEqual($column, $value);

        return $this;
    }

    public function expr($expr)
    {
        $this->where->expr($expr);

        return $this;
    }

    public function exists($value)
    {
        $this->where->exists($value);

        return $this;
    }

    public function notExists($value)
    {
        $this->where->notExists($value);

        return $this;
    }

    public function between($column, $begin, $end)
    {
        $this->where->between($column, $value);

        return $this;
    }

    public function notBetween($column, $begin, $end)
    {
        $this->where->notBetween($column, $value);

        return $this;
    }

    // /**
    //  * Add join statement to select.
    //  *
    //  * @param string|array $with table
    //  * @param string $on
    //  * @param string $type
    //  *
    //  * @throws \Exception
    //  *
    //  * @return $this
    //  */
    // public function join($with, $on, $type = self::JOIN_LEFT)
    // {
    //     $table = null;
    //     $alias = null;

    //     if (is_array($with)) {
    //         $keys = array_keys($with);

    //         $table = $with[$keys[0]];
    //         $alias = $keys[0];
    //     }elseif(is_string($with)){
    //         $table = $with;
    //         $alias = $with;
    //     }else{
    //         throw new \Exception("Unsupported type of with for join.");
    //     }

    //     $this->joins[$alias] = array(
    //         'type' => $type,
    //         'table' => $table,
    //         'alias' => $alias,
    //         'on' => $on,
    //     );

    //     return $this;
    // }

    /**
     * Add join statement to select.
     *
     * @param string $type
     * @param mixed $table
     * @param mixed $on
     * @param string $alias
     */
    public function join(string $type,  $table, $on, string $alias = null)
    {
        if (!is_array($on)) {
            $on = array($on);
        }

        $this->joins[] = array(
            "type" => $type,
            "table" => $table,
            "on" => $on,
            "alias" => $alias,
        );

        return $this;
    }

    /**
     * Add limit statement to select.
     *
     * @param int $limit
     * @param int $offset
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function limit(int $limit, int $offset = 0)
    {
        $this->limit = array(
            'limit' => $limit,
            'offset' => $offset,
        );

        return $this;
    }

    /**
     * Set paggination for result.
     *
     * ```php
     *
     * // We can user array as param.
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->order('id asc')
     *     ->page(array(
     *         'page' => 0,
     *         'pageSize' => 2,
     *     ))
     *     ->fetch()
     * ;
     *
     * // Or use params
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->order('id asc')
     *     ->page(1, 2)
     *     ->fetch()
     * ;
     *
     * // Or use string
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->order('id asc')
     *     ->page('1,2')
     *     ->fetch()
     * ;
     *
     * ```
     *
     * @param mixed $page
     * @param mixed $pageSize
     *
     * @return $this
     */
    public function page($page, $pageSize = null)
    {
        if (is_array($page)) {
            if (array_key_exists('page', $page) && array_key_exists('pageSize', $page)) {
                return $this->page($page['page'], $page['pageSize']);
            } else {
                return $this;
            }
        } elseif (is_string($page) && is_null($pageSize)) {
            $exploded = explode(',', $page);

            if (count($exploded) == 2) {
                return $this->page($exploded[0], $exploded[1]);
            } else {
                throw new \InvalidArgumentException("Page as string should be write as page,size");
            }
        }

        if (is_numeric($page) && is_numeric($pageSize)) {
            if ($pageSize > 0 && $page >= 0) {
                if ($page == 0) {
                    $this->limit($pageSize);
                }else{
                    $this->limit($pageSize, $page * $pageSize);
                }
            }else{
                throw new \InvalidArgumentException("Page should be 0 or larger and pageSize should be large then 0.");
            }
        }else{
            return $this;
            // throw new \InvalidArgumentException("Page and pageSize shuld be number.");
        }

        return $this;
    }

    /**
     * Add group by statement for select. Group are given by list.
     *
     * @param mixed $group
     * @return $this
     */
    public function group($group)
    {
        if (is_array($group)) {
            $this->group = $group;
        } else {
            $this->group = array($group);
        }

        return $this;
    }

    public function having($having)
    {
        if (is_array($having)) {
            $this->having = $having;
        } else {
            $this->having = array($having);
        }

        return $this;
    }

    /**
     * Add order statement to select.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->limit(10)
     *     ->order(array("id asc", "name desc"))
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->order('id desc')
     *     ->limit(10)
     *     ->fetch()
     * ;
     *
     * // Or we can use Expr
     * $select->order(new \Tiie\Data\Adapters\Commands\SQL\Expr("RAND()"));
     *
     * ```
     * @param mixed $column
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function order($order)
    {
        if (is_array($order)) {
            $this->order = $order;
        } else {
            $this->order = array($order);
        }

        return $this;
    }

    /**
     * Alias for 'order()'.
     *
     * @return $this
     */
    public function sort($sort)
    {
        return $this->order($sort);
    }

    // public function sort($sort = null)
    // {
    //     if(is_null($sort)) {
    //         return $this->order;
    //     } elseif (is_array($sort)) {
    //         if (empty($sort)) {
    //             $this->order = array();

    //             return $this;
    //         }

    //         if (!is_numeric(array_keys($sort)[0])) {
    //             $sort = array($sort);
    //         }

    //         $this->order = array();

    //         // $this->sort("firstName asc");
    //         // $this->sort(array('firstName' => 'asc'));
    //         // $this->sort(array(
    //         //     array('firstName' => 'asc'),
    //         //     array('firstName' => 'asc'),
    //         //     array('firstName' => 'asc'),
    //         // ));
    //         // $this->sort(array(
    //         //     'field' => 'firstName',
    //         //     'type' => 'asc',
    //         // ));

    //         // $this->sort(array(
    //         //     array(
    //         //         'field' => 'firstName',
    //         //         'type' => 'asc',
    //         //     ),
    //         //     array(
    //         //         'field' => 'firstName',
    //         //         'type' => 'asc',
    //         //     ),
    //         // ));
    //         // $this->sort(array(
    //         //     "firstName asc",
    //         //     array("firstName" => 'asc'),
    //         //     array(
    //         //         'field' => 'firstName',
    //         //         'type' => 'asc',
    //         //     ),
    //         // );

    //         // if (count($sort) == 1) {
    //         //     $sort = array($sort);
    //         // }

    //         foreach ($sort as $key => $value) {
    //             if (is_string($value)) {
    //                 // $this->sort(array("name asc"));
    //                 $value = explode(' ', trim($value));

    //                 $field = array_shift($value);
    //                 $type = 'asc';

    //                 foreach ($value as $e) {
    //                     if (!empty($e)) {
    //                         $type = $e;
    //                         break;
    //                     }
    //                 }

    //                 $this->order[] = array(
    //                     'field' => $field,
    //                     'type' => $type,
    //                 );
    //             } elseif(is_array($value)){
    //                 // $this->sort(array(
    //                 //     array('name asc'),
    //                 //     array('name' => 'asc'),
    //                 //     array(
    //                 //         'field' => 'name',
    //                 //         'type' => 'asc',
    //                 //     ),
    //                 // ));

    //                 $keys = array_keys($value);

    //                 if (count($keys) == 1 && is_numeric($keys[0])) {
    //                     // $this->sort(array("name asc"));
    //                     $value = explode(' ', trim($value[$keys[0]]));

    //                     $field = array_shift($value);
    //                     $type = 'asc';

    //                     foreach ($value as $e) {
    //                         if (!empty($e)) {
    //                             $type = $e;
    //                             break;
    //                         }
    //                     }

    //                     $this->order[] = array(
    //                         'field' => $field,
    //                         'type' => $type,
    //                     );
    //                 } elseif (count($keys) == 1 && !is_numeric($keys[0])) {
    //                     $this->order[] = array(
    //                         'field' => $keys[0],
    //                         'type' => $value[$keys[0]],
    //                     );
    //                 } else if(!empty($value['field']) && !empty($value['type'])) {
    //                     $this->order[] = array(
    //                         'field' => $value['field'],
    //                         'type' => $value['type'],
    //                     );
    //                 } else {
    //                     trigger_error("No order field.", E_USER_NOTICE);
    //                 }
    //             }
    //         }

    //         return $this;
    //     } elseif (is_string($sort)) {
    //         $sort = explode(' ', trim($sort));

    //         $field = array_shift($sort);
    //         $type = 'asc';

    //         foreach ($sort as $value) {
    //             if (!empty($value)) {
    //                 $type = $value;
    //                 break;
    //             }
    //         }

    //         $this->order = array(
    //             array(
    //                 'field' => $field,
    //                 'type' => $type,
    //             )
    //         );

    //         return $this;
    //     }
    // }

    /**
     * Return or set columns for select.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->columns(array(
     *         'id',
     *         'firstName',
     *         'fullName' => new Expr("concat(id, '-', firstName, '-', lastName)")
     *     ))
     *     ->limit(2)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     * ```
     *
     * @param array $column Array with columns.
     *
     * @return $this|array
     */
    public function columns(array $columns = null)
    {
        if (is_null($columns)) {
            return $this->columns;
        }else{
            foreach ($columns as $key => $column) {
                if (is_numeric($key)) {
                    $this->column($column);
                }else{
                    $this->column($column, $key);
                }
            }

            return $this;
        }
    }

    /**
     * Add column to Select statement.
     *
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('firstName')
     *     ->column('lastName')
     *     ->limit(2)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id', 'userId')
     *     ->column(new Expr("concat(id, '-', firstName, '-', lastName)"), 'fullName')
     *     ->limit(2)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     * ```
     *
     * @param mixed $column
     * @param string $alias
     * @return $this;
     */
    public function column($column, string $alias = null)
    {
        $this->columns[] = array(
            "column" => $column,
            "alias" => $alias,
        );

        return $this;
    }

    /**
     * Set from for Select statement. From can be table name or other sub select.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->column('u.lastName')
     *     ->limit(2)
     *     ->order('id asc')
     *     ->fetch()
     * ;
     *
     * // With sub select
     * $sub = (new Select())
     *     ->from('users', 'sub')
     *     ->column('id')
     *     ->column('email')
     *     ->limit(10)
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from($sub, 'base')
     *     ->order('base.id asc')
     *     ->fetch()
     * ;
     * ```
     *
     * @param mixed $table
     * @param string $alias
     *
     * @return $this
     */
    public function from($table, $alias = null)
    {
        $this->from = array(
            'table' => $table,
            'alias' => $alias,
        );

        return $this;
    }

    public function brackets($function)
    {
        $this->where->brackets($function, $this);

        return $this;
    }

    public function and()
    {
        $this->where->and();

        return $this;
    }

    public function or()
    {
        $this->where->or();

        return $this;
    }

    public function conditions($column, $conditions, array $params = array())
    {
        $this->where->conditions($column, $conditions, $params);

        return $this;
    }

    public function params(array $values = array(), array $fields = array())
    {
        $this->where->params($values, $fields);

        return $this;
    }

    public function build(array $params = array()) : Built
    {
        $command = "SELECT";
        $string;
        $bl;
        $vars = $this->binds();

        if (!empty($this->columns)) {
            foreach($this->columns as $column) {
                $resolved = $this->common->resolveColumn($column["column"]);

                if (is_null($resolved)) {
                    trigger_error(sprintf("Inproper column '%s'", Functions::inline($column["column"])), E_USER_WARNING);
                } else {
                    if (!empty($column["alias"])) {
                        $resolved["alias"] = $column["alias"];
                    }

                    $column = $resolved;
                }

                $string = "";

                if(is_string($column["column"])) {
                    if(!empty($column["table"]) && !empty($column["column"])) {
                        $string .= "    `{$column["table"]}`.`{$column["column"]}`";
                    } else if(!empty($column["column"])) {
                        $string .= "    `{$column["column"]}`";
                    }

                    if (!empty($column["alias"])) {
                        $command .= "\n    {$string} as `{$column["alias"]}`,";
                    } else {
                        $command .= "\n    {$string},";
                    }
                } else if(
                    $column["column"] instanceof Where ||
                    $column["column"] instanceof Select ||
                    $column["column"] instanceof Expr
                ) {
                    if (empty($column["alias"])) {
                        trigger_error("There should be alias if column is Where or Select.", E_USER_WARNING);
                        continue;
                    }

                    $bl = $column["column"]->build();
                    $vars = array_merge($vars, $bl->params());

                    $command .= sprintf("\n    (%s) as `%s`,", $bl->command(), $column["alias"]);
                } else {
                    trigger_error(sprintf("Inproper column '%s'", Functions::inline($column["column"])), E_USER_WARNING);
                }
            }

            $command = substr($command, 0, strlen($command) - 1);
        } else {
            $command .= "\n    *";
        }

        // From
        if (!empty($this->from)) {
            $from = $this->common->resolveTable($this->from["table"]);

            if (is_null($from)) {
                trigger_error(sprintf("Inproper FROM for SELECT '%s'", Functions::inline($this->from)), E_USER_WARNING);
            } else {
                if (!empty($this->from["alias"])) {
                    $from["alias"] = $this->from["alias"];
                }

                if(is_string($from["table"])) {
                    if(!empty($from["table"]) && !empty($from["alias"])) {
                        $command .= "\nFROM `{$from["table"]}` as `{$from["alias"]}`";
                    } else if(!empty($from["table"])) {
                        $command .= "\nFROM `{$from["table"]}`";
                    }
                } else if(
                    $from["table"] instanceof Select ||
                    $from["table"] instanceof Expr
                ) {
                    $bl = $from["table"]->build();

                    $vars = array_merge($vars, $bl->params());

                    if (!empty($from["alias"])) {
                        $command .= sprintf("\nFROM (\n%s\n) as `%s`", $bl->command(), $from["alias"]);
                    } else {
                        $command .= sprintf("\nFROM (\n%s\n)", $bl->command());
                    }
                } else {
                    trigger_error(sprintf("Inproper FROM for SELECT '%s'", Functions::inline($from)), E_USER_WARNING);
                }
            }
        } else {
            trigger_error("Please define FROM for SELECT.", E_USER_WARNING);
        }

        // Joins
        foreach ($this->joins as $join) {
            $type = strtoupper($join["type"]);

            $table = "";
            $on = "";

            $resolved = $this->common->resolveTable($join["table"]);

            if (is_null($resolved)) {
                trigger_error(sprintf("Inproper TABLE for JOIN '%s'", Functions::inline($join["table"])), E_USER_WARNING);
                continue;
            }

            if (!empty($join["alias"])) {
                $resolved["alias"] = $join["alias"];
            }

            // Table
            if(is_string($resolved["table"])) {
                if(!empty($resolved["table"]) && !empty($resolved["alias"])) {
                    $table .= "`{$resolved["table"]}` as `{$resolved["alias"]}`";
                } else if(!empty($resolved["table"])) {
                    $table .= "`{$resolved["table"]}`";
                }

            } else if(
                $resolved["table"] instanceof Select ||
                $resolved["table"] instanceof Expr
            ) {
                if (empty($resolved["alias"])) {
                    trigger_error("There should be alias if there is join with Select, Expr.", E_USER_WARNING);

                    continue;
                }

                $bl = $resolved["table"]->build();
                $vars = array_merge($vars, $bl->params());

                if (!empty($join["alias"])) {
                    $table .= sprintf("(%s) as `%s`", $bl->command(), $join["alias"]);
                } else {
                    $table .= sprintf("(%s)", $bl->command());
                }
            } else {
                trigger_error("Unssuported type of table for table.", E_USER_WARNING);
            }

            foreach($join["on"] as $condition) {
                // on
                if(is_string($condition)) {
                    $on .= "{$condition} AND ";
                } else if(
                    $condition instanceof Select ||
                    $condition instanceof Where ||
                    $condition instanceof Expr
                ) {
                    $bl = $condition->build();
                    $vars = array_merge($vars, $bl->params());

                    $on .= sprintf("(%s) AND ", $bl->command());
                } else {
                    trigger_error(sprintf("Unsupported type of ON for JOIN '%s'", Functions::inline($condition)), E_USER_WARNING);
                    continue;
                }
            }

            if (!empty($on)) {
                $on = substr($on, 0, strlen($on) - 5);

                $command .= "\n{$type} JOIN {$table} on {$on}";
            } else {
                $command .= "\n{$type} JOIN {$table}";
            }
        }

        $bl = $this->where->build();
        if (!empty($bl->command())) {
            $vars = array_merge($vars, $bl->params());

            $command .= "\nWHERE {$bl->command()}";
        }

        // Order
        if (!empty($this->order)) {
            $string = "";
            foreach ($this->order as $order) {
                $order = $this->common->resolveOrder($order);

                if (!empty($order["table"]) && !empty($order["column"])) {
                    $string .= "`{$order["table"]}`.`{$order["column"]}` {$order["type"]},";
                } else if(!empty($order["column"])) {
                    $string .= "`{$order["column"]}` {$order["type"]},";
                }
            }

            if (!empty($string)) {
                $string = substr($string, 0, strlen($string) - 1);
                $command .= "\nORDER BY {$string}";
            }
        }

        // Group
        if (!empty($this->group)) {
            $string = "";
            foreach ($this->group as $group) {
                $group = $this->common->resolveColumnName($group);

                if (!empty($group["table"]) && !empty($group["column"])) {
                    $string .= "`{$group["table"]}`.`{$group["column"]}`,";
                } else if(!empty($group["column"])) {
                    $string .= "`{$group["column"]}`,";
                }
            }

            if (!empty($string)) {
                $string = substr($string, 0, strlen($string) - 1);
                $command .= "\nGROUP BY {$string}";
            }
        }

        // Having
        if (!empty($this->having)) {
            $having = "";

            foreach ($this->having as $condition) {
                if (is_string($condition)) {
                    // $having .= "{$condition} AND ";
                    $having .= sprintf("(%s) AND ", $condition);
                } else {
                    trigger_error(sprintf("Unsupported type of HAVING '%s'", Functions::inline($condition)), E_USER_WARNING);
                    continue;
                }
            }

            if (!empty($having)) {
                $having = substr($having, 0, strlen($having) - 5);

                $command .= "\nHAVING {$having}";
            }
        }

        // Limit
        if (!empty($this->limit)) {
            if (is_numeric($this->limit["limit"]) || !is_numeric($this->limit["offset"])) {
                $command .= "\nLIMIT {$this->limit["limit"]} OFFSET {$this->limit["offset"]}";
            }
        }

        return new Built($command, $vars);
    }

    /**
     * Built SQL command.
     */
    public function buildOld(array $params = array()) : Built
    {
        $buildCommand = new Built();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        // todo Zapisac jako $sql = new \stdClass poszczegolne elementy. W
        // ogole popraw budowanie zapytania
        // zapytania
        $scolumns = '';
        $sfrom = '';
        $sjoins = '';
        $swhere = '';
        $slimit = '';
        $sorder = '';
        $slimit = '';
        $sgroup = '';
        $shaving = '';

        if (empty($this->columns)) {
            $scolumns .= '*';
        }else{
            foreach ($this->columns as $cid => $c) {
                if (is_string($c['column'])) {
                    $column = \Tiie\Data\functions::columnStr($c['column'], $params);
                }else{
                    $column = $c['column'];
                }

                if (!is_null($c['alias'])) {
                    $scolumns .= "{$column} as {$params['quote']}{$c['alias']}{$params['quote']},\n";
                }else{
                    $scolumns .= "{$column},\n";
                }
            }

            $scolumns = trim($scolumns, ",\n");
        }

        // from
        if (!is_null($this->from)) {
            if ($this->from['table'] instanceof Select) {
                // If from is other select then this select is sub select.
                $tablecommand = $this->from['table']->build($params);

                if (is_null($this->from['alias'])) {
                    throw new \InvalidArgumentException("alias is required when from is other select.");
                }

                // merge params
                $buildCommand->params($tablecommand->params());

                $sfrom .= "({$tablecommand->command()}) as {$params['quote']}{$this->from['alias']}{$params['quote']}";
            } else {
                if ($this->from['table'] != $this->from['alias']) {
                    $sfrom .= "{$params['quote']}{$this->from['table']}{$params['quote']} as {$params['quote']}{$this->from['alias']}{$params['quote']}";
                }else{
                    $sfrom .= "{$params['quote']}{$this->from['table']}{$params['quote']}";
                }
            }
        }

        // Creating joins
        if (!empty($this->joins)) {
            foreach ($this->joins as $key => $join) {
                $joint = null;

                switch ($join['type']) {
                case self::JOIN_INNER:
                    $joint = 'inner join';
                    break;
                case self::JOIN_RIGHT:
                    $joint = 'right join';
                    break;
                case self::JOIN_LEFT:
                    $joint = 'left join';
                    break;
                case self::JOIN_OUTER:
                    $joint = 'outer join';
                    break;
                default:
                    throw new \Exception(sprintf("Unsupported type of join %s", $join['type']));
                }

                if ($join['table'] instanceof Select) {
                    $joincommand = $join['table']->build();

                    // merge params
                    $buildCommand->params($joincommand->params());

                    $join['table'] = sprintf("(%s)", $joincommand->command());

                    if (empty($join['alias'])) {
                        throw new \Exception("Join with other select, needs to defined alias.");
                    }

                    $sjoins .= "{$joint} {$join['table']} as {$params['quote']}{$join['alias']}{$params['quote']} on {$join['on']}\n";
                }else{
                    $sjoins .= "{$joint} {$params['quote']}{$join['table']}{$params['quote']} as {$params['quote']}{$join['alias']}{$params['quote']} on {$join['on']}\n";
                }
            }

            $sjoins = trim($sjoins, "\n");
        }

        // where
        $where = $this->where->build($params);
        if (!is_null($where->command())) {
            $buildCommand->params($where->params());
            $swhere = $where->command();
        }

        if (!empty($this->order)) {
            foreach ($this->order as $order) {
                $column = \Tiie\Data\functions::columnStr($order['field'], $params);

                $orderType = strtolower($order['type']);

                if (!in_array($orderType, array('asc', 'desc'))) {
                    throw new \InvalidArgumentException("Invalid type of order. Allowe is asc|desc");
                }

                $sorder .= "{$column} {$order['type']},";
            }

            $sorder = trim($sorder, ',');
        }

        if (!is_null($this->limit)) {
            $slimit .= "{$this->limit['limit']} offset {$this->limit['offset']}";
        }

        if (!empty($this->group)) {
            foreach ($this->group as $c) {
                $column = \Tiie\Data\functions::columnStr($c, $params);
                $sgroup .= "{$column},";
            }

            $sgroup = trim($sgroup, ',');
        }

        if (!empty($this->having)) {
            $shaving = trim($this->having);
        }

        // create select
        $sql = 'select';

        if(!empty($scolumns)){
            $sql = "{$sql}\n$scolumns";
        }else{
            throw new \Exception(sprintf("Please define columns for select."));
        }

        if(!empty($sfrom)){
            $sql = "{$sql}\nfrom $sfrom";
        }else{
            throw new \Exception(sprintf("Please define from for select."));
        }

        if(!empty($sjoins)){
            $sql = "{$sql}\n$sjoins";
        }

        if(!empty($swhere)){
            $sql = "{$sql}\n$swhere";
        }

        if(!empty($sorder)){
            $sql = "{$sql}\norder by $sorder";
        }

        if(!empty($sgroup)){
            $sql = "{$sql}\ngroup by$sgroup";
        }

        if(!empty($shaving)){
            $sql = "{$sql}\nhaving $shaving";
        }

        if(!empty($slimit)){
            $sql = "{$sql}\nlimit $slimit";
        }

        $buildCommand->params($this->binds());
        $buildCommand->command($sql);

        return $buildCommand;
    }

    public function count(array $params = array())
    {
        $adapter = $this->adapter();

        if (is_null($adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $adapter->count($this, $params);
    }

    public function random()
    {
        $this->order(new \Tiie\Data\Adapters\Commands\SQL\Expr("RAND()"));

        return $this;
    }

    public function fetch(array $params = array())
    {
        $adapter = $this->adapter();

        if (is_null($adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $adapter->fetch($this, $params);
    }
}
