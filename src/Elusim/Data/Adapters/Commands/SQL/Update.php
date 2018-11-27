<?php
namespace Elusim\Data\Adapters\Commands\SQL;

use Elusim\Data\Adapters\Commands\Command;
use Elusim\Data\Adapters\AdapterInterface;
use Elusim\Data\Adapters\Commands\SQL\Where;

class Update extends Command
{
    private $table = null;
    private $values = array();
    private $where;

    function __construct(AdapterInterface $adapter = null)
    {
        parent::__construct($adapter);

        $this->where = new Where();
    }

    /**
     * Set table for UPDATE statement.
     *
     * ```php
     * $update = new Update();
     * $update->table('users');
     * ```
     *
     * @param string $table
     * @return $this|string
     *
     */
    public function table(string $table = null)
    {
        if (!is_null($table)) {
            $this->table = $table;

            return $this;
        }else{
            return $this->table;
        }
    }

    /**
     * Set values for UPDATE statement.
     *
     * ```php
     * $update = new Update();
     *
     * $this->assertEquals($update, $update->values(array(
     *     'id' => 1,
     *     'name' => 'Pawel',
     * )));
     * ```
     *
     * @param array $values
     * @return $this|array
     */
    public function values(array $values = null)
    {
        if (is_null($values)) {
            return $this->values;
        }else{
            $this->values = $values;

            return $this;
        }
    }

    /**
     * Set one value at values of UPDATE.
     *
     * ```php
     * $update = new Update();
     *
     * $update->values(array(
     *     'id' => 1,
     *     'name' => 'Pawel',
     * ));
     * ```
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value)
    {
        $this->values[$name] = $value;

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

    public function build(array $params = array())
    {
        if (is_null($this->table)) {
            throw new \Exception("Insert can not be build, bacause table is not defined.");
        }

        $command = new \Elusim\Data\Adapters\Commands\BuiltCommand();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        $sql = "update {$params['quote']}{$this->table}{$params['quote']}";

        $sset = "set ";

        foreach ($this->values as $key => $value) {
            $column = \Elusim\Data\functions::columnStr($key, $params);
            $s = null;

            if (is_numeric($value)) {
                $s = $value;
            }else if(is_string($value)){
                $uid = $this->uid();
                $s = ":{$uid}";
                $command->param($uid, $value);
            }else{
                throw new \Exception(sprintf("Unsupported type of update value %s", gettype($value)));
            }

            $sset .= "{$column} = {$s},";
        }

        $sset = trim($sset, ',');


        if (!empty($sset)) {
            $sql .= " $sset";
        }

        $where = $this->where->build($params);

        if (!is_null($where->command())) {
            $sql .= " {$where->command()}";
            $command->params($where->params(), 1);
        }

        $command->command($sql);

        return $command;
    }
}
