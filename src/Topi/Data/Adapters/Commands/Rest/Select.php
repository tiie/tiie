<?php
namespace Elusim\Data\Adapters\Commands\SQL;

use Elusim\Data\Adapters\Commands\Command;
use Elusim\Data\Adapters\Commands\BuiltCommand;
use Elusim\Data\Adapters\AdapterInterface;
use Elusim\Data\Adapters\Commands\SQL\Where;

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

    private $rules = array();

    /**
     * Podastawowa regula.
     */
    private $defaultRuleFun;

    protected $where = null;
    protected $pw = null;

    function __construct(AdapterInterface $adapter = null)
    {
        parent::__construct($adapter);

        $this->where = new Where();

        $this->defaultRuleFun = function($values, $select){
            foreach ($values as $name => $value) {
                $select->eq($name, $value);
            }

            return true;
        };
    }

    public function __clone()
    {
        $this->where = clone($this->where);
    }

    public function defaultRule($defaultRule)
    {
        $this->defaultRuleFun = $defaultRule;

        return $this;
    }

    public function rule($params, $rule = null)
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

    public function process($params = array())
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

    public function eq($column, $value)
    {
        $this->where->eq($column, $value);

        return $this;
    }

    public function equal($column, $value)
    {
        $this->where->equal($column, $value);

        return $this;
    }

    public function neq($column, $value)
    {
        $this->where->neq($column, $value);

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

    /**
     * Add join statement to select.
     *
     * @param string|array $with table
     * @param string $on
     * @param string $type
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function join($with, $on, $type = self::JOIN_LEFT)
    {
        $table = null;
        $alias = null;

        if (is_array($with)) {
            // $select->join(array('of' => $select))
            // $select->join(array('of' => 'offers'))

            $keys = array_keys($with);

            $table = $with[$keys[0]];
            $alias = $keys[0];
        }elseif(is_string($with)){
            $table = $with;
            $alias = $with;
        }else{
            throw new \Exception("Unsupported type of with for join.");
        }

        $this->joins[$alias] = array(
            'type' => $type,
            'table' => $table,
            'alias' => $alias,
            'on' => $on,
        );

        return $this;
    }

    // todo : dodac czyszcze calego selecta
    public function clean()
    {
        $this->where->clean();

        return $this;
    }

    public function leftJoin($with, $on)
    {
        return $this->join($with, $on, self::JOIN_LEFT);
    }

    public function rightJoin($with, $on)
    {
        return $this->join($with, $on, self::JOIN_RIGHT);
    }

    public function outerJoin($with, $on)
    {
        return $this->join($with, $on, self::JOIN_OUTER);
    }

    public function innerJoin($with, $on)
    {
        return $this->join($with, $on, self::JOIN_INNER);
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
        if ($limit < 0) {
            throw new \InvalidArgumentException("Limit should be larger than 0");
        }

        if ($offset < 0) {
            throw new \InvalidArgumentException("Offset should be larger than 0");
        }

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
            if (isset($page['page']) && isset($page['pageSize'])) {
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
            throw new \InvalidArgumentException("Page and pageSize shuld be number.");
        }

        return $this;
    }

    public function group($column)
    {
        $this->group[] = $column;

        return $this;
    }

    public function having($having)
    {
        $this->having = $having;

        return $this;
    }

    /**
     * Add order statement to select.
     *
     * ```php
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users')
     *     ->column('id')
     *     ->order('id', 'asc')
     *     ->limit(10)
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
     * $select->order(new \Elusim\Data\Adapters\Commands\SQL\Expr("RAND()"));
     *
     * ```
     * @param mixed $column
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function order($column, string $type = 'asc')
    {
        if (is_string($column)) {
            $column = explode(' ', $column);

            if (count($column) > 1) {
                if (count($column) != 2) {
                    throw new \InvalidArgumentException("Invalid type of order. Allowe is 'column asc|desc'");
                }

                $type = $column[1];
            }

            $type = strtolower($type);

            $this->order[] = array(
                'column' => $column[0],
                'type' => $type,
            );
        } else {
            $this->order[] = array(
                'column' => $column,
                'type' => $type,
            );
        }

        // if (!in_array($type, array('desc', 'asc'))) {
        //     throw new \InvalidArgumentException("Unsupported type of order {$type}.");
        // }

        return $this;
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
        $cid = !is_null($alias) ? $alias : count($this->columns);

        $this->columns[$cid] = array(
            'column' => $column,
            'alias' => $alias,
        );

        return $this;
    }

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
                    // $this->column($key, $column);
                }
            }

            return $this;
        }
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
        if (is_null($alias) && is_string($alias)) {
            $alias = $table;
        }

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

    public function ando()
    {
        $this->where->ando();

        return $this;
    }

    public function oro()
    {
        $this->where->oro();

        return $this;
    }

    public function conditions($column, $conditions, array $params = array())
    {
        $this->where->conditions($column, $conditions, $params);

        return $this;
    }

    public function params(array $values = array(), array $fields = array())
    {
        if (array_key_exists('order', $values)) {
            $this->order($values['order']);

            unset($values['order']);
        } elseif (array_key_exists('sort', $values)) {
            $this->order($values['sort']);

            unset($values['sort']);
        }

        if (array_key_exists('id', $values)) {
            $this->where->params(array(
                'id' => $values['id'],
            ), $fields);
        } else {
            $this->where->params($values, $fields);

            if (array_key_exists('page', $values) && array_key_exists('pageSize', $values)) {
                $this->page($values['page'], $values['pageSize']);
            } elseif (array_key_exists('page', $values)) {
                $this->page($values['page']);
            }

            if (array_key_exists('limit', $values)) {
                $this->limit($values['limit']);
                unset($values['limit']);
            }
        }

        return $this;
    }

    /**
     * Add 'column < value' statement to where.
     *
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->lte('u.id', 5)
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->lte('u.id', new Expr('5'))
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     *
     * ```
     */
    public function lt($column, $value)
    {
        $this->where->lt($column, $value);

        return $this;
    }

    /**
     * Add 'column <= value' statement to where.
     *
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->lt('u.id', 5)
     *     ->order('id asc')
     *     ->limit(4)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->lt('u.id', new Expr('5'))
     *     ->order('id asc')
     *     ->limit(4)
     *     ->fetch()
     * ;
     * ```
     */
    public function lte($column, $value)
    {
        $this->where->lte($column, $value);

        return $this;
    }

    /**
     * Add 'column > value' statement to where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->gt('u.id', 5)
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->gt('u.id', new Expr('5'))
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     * ```
     */
    public function gt($column, $value)
    {
        $this->where->gt($column, $value);

        return $this;
    }

    /**
     * Add 'column >= value' statement to where.
     *
     * ```php
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->gte('u.id', 5)
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     *
     * $rows = (new Select($this->adapter('bookshop')))
     *     ->from('users', 'u')
     *     ->column('u.id')
     *     ->column('u.firstName')
     *     ->gte('u.id', new Expr('5'))
     *     ->order('id asc')
     *     ->limit(5)
     *     ->fetch()
     * ;
     * ```
     */
    public function gte($column, $value)
    {
        $this->where->gte($column, $value);

        return $this;
    }

    /**
     * Build SQL command.
     */
    public function build(array $params = array())
    {
        $buildCommand = new BuiltCommand();

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
                    $column = \Elusim\Data\functions::columnStr($c['column'], $params);
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
                $column = \Elusim\Data\functions::columnStr($order['column'], $params);

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
                $column = \Elusim\Data\functions::columnStr($c, $params);
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

    public function count($params = array())
    {
        $adapter = $this->adapter();

        if (is_null($adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $adapter->count($this, $params);
    }

    public function random()
    {
        $this->order(new \Elusim\Data\Adapters\Commands\SQL\Expr("RAND()"));

        return $this;
    }

    public function fetch($format = 'all', $params = array())
    {
        $adapter = $this->adapter();

        if (is_null($adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $adapter->fetch($this, $format, $params);
    }
}
