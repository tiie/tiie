<?php
namespace Topi\Data\Adapters\Commands\SQL;

use Topi\Data\Adapters\Commands\Command;

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

    function __construct(\Topi\Data\Adapters\AdapterInterface $adapter = null)
    {
        parent::__construct($adapter);
        $this->where = new \Topi\Data\Adapters\Commands\SQL\Where();

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
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function limit(int $limit, int $offset = 0)
    {
        if (is_numeric($limit) && is_numeric($offset)) {
            if ($limit <= 0) {
                throw new \InvalidArgumentException("Limit should be larger than 0");
            }

            if ($offset < 0) {
                throw new \InvalidArgumentException("Offset should be larger than 0");
            }

            $this->limit = array(
                'limit' => $limit,
                'offset' => $offset,
            );
        }else{
            throw new \InvalidArgumentException("Limit and Offset should be numberic.");
        }

        return $this;
    }

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
                return $this;
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
                throw new \InvalidArgumentException("page should be 0 or larger and pageSize should be large then 0.");
            }

        }else{
            throw new \InvalidArgumentException("page and pageSize shuld be number.");
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
     * Metoda dodaje sortowanie do zapytania.
     *
     * @param string $column
     * @param string $type
     * @return $this
     */
    public function order($column, $type = 'asc')
    {
        if(is_string($column)) {
            // Jeśli nie podano typu, to próbuje odczytać typ z kolumny.
            $column = explode(' ', $column);

            if (count($column) > 1) {
                $type = $column[1];
            }

            $this->order[] = array(
                'column' => $column[0],
                'type' => $type,
            );
        }else {
            $this->order[] = array(
                'column' => $column,
                'type' => $type,
            );
        }

        return $this;
    }

    public function column($column, $alias = null)
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
     * @param array $column Array with columns.
     *     u : users
     *     dic : dictionaries
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
                    // $this->column($column, $key);
                    $this->column($key, $column);
                }
            }

            return $this;
        }
    }

    public function from($table, $alias = null)
    {
        if (is_null($alias)) {
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

    public function endWith($column, $value)
    {
        $this->where->endWith($column, $value);

        return $this;
    }

    public function contains($column, $value)
    {
        $this->where->contains($column, $value);

        return $this;
    }

    public function like($column, $value)
    {
        $this->where->like($column, $value);

        return $this;
    }

    public function conditions($column, $conditions, array $params = array())
    {
        $this->where->conditions($column, $conditions, $params);

        return $this;
    }

    public function params(array $values = array(), array $fields = array())
    {
        if (array_key_exists('page', $values)) {
            $this->page($values['page']);

            unset($values['page']);
        }

        if (array_key_exists('limit', $values)) {
            $this->limit($values['limit']);
            unset($values['limit']);
        }

        if (array_key_exists('order', $values)) {
            $this->order($values['order']);

            unset($values['order']);
        }

        if (array_key_exists('sort', $values)) {
            $this->order($values['sort']);

            unset($values['sort']);
        }

        $this->where->params($values, $fields);

        return $this;
    }

    public function eq($column, $value)
    {
        $this->where->eq($column, $value);

        return $this;
    }

    public function neq($column, $value)
    {
        $this->where->neq($column, $value);

        return $this;
    }

    public function lt($column, $value)
    {
        $this->where->lt($column, $value);

        return $this;
    }

    public function lte($column, $value)
    {
        $this->where->lte($column, $value);

        return $this;
    }

    public function gt($column, $value)
    {
        $this->where->gt($column, $value);

        return $this;
    }

    public function gte($column, $value)
    {
        $this->where->gte($column, $value);

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
        $this->where->between($column, $begin, $end);

        return $this;
    }

    /**
     * Build SQL command.
     */
    public function build(array $params = array())
    {
        $command = new \Topi\Data\Adapters\Commands\BuiltCommand();

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
                    $column = \Topi\Data\functions::columnStr($c['column'], $params);
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

        if (!is_null($this->from)) {
            if ($this->from['table'] instanceof Select) {
                $tablecommand = $this->from['table']->build($params);

                if (is_null($this->from['alias'])) {
                    throw new \Exception("alias is required when from is other select.");
                }

                // merge params
                $command->params($tablecommand->params());

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
                    $command->params($joincommand->params());

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

        $where = $this->where->build($params);
        if (!is_null($where->command())) {
            $command->params($where->params(), 1);
            $swhere = $where->command();
        }

        if (!empty($this->order)) {
            foreach ($this->order as $order) {
                $column = \Topi\Data\functions::columnStr($order['column'], $params);
                $sorder .= "{$column} {$order['type']},";
            }

            $sorder = trim($sorder, ',');
        }

        if (!is_null($this->limit)) {
            $slimit .= "{$this->limit['limit']} offset {$this->limit['offset']}";
        }

        if (!empty($this->group)) {
            foreach ($this->group as $c) {
                $column = \Topi\Data\functions::columnStr($c, $params);
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

        $command->command($sql);

        return $command;
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
        $this->order(new \Topi\Data\Adapters\Commands\SQL\Expr("RAND()"));

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
