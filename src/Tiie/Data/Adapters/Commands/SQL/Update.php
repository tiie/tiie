<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\AdapterInterface;
use Tiie\Data\Adapters\Commands\SQL\Where;
use Tiie\Data\Adapters\Commands\Built;

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
     * $update->setTable('users');
     * ```
     *
     * @param string $table
     * @return $this|string
     *
     */
    public function setTable(string $table = null)
    {
        $this->table = $table;

        return $this;
    }

    public function setValues(array $values) : void
    {
        $this->values = $values;
    }

    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Set one value at values of UPDATE.
     *
     * ```php
     * $update = new Update();
     *
     * $update->setValues(array(
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

    public function lowerThanEqual($column, $value)
    {
        $this->where->lowerThanEqual($column, $value);

        return $this;
    }

    public function greaterThan($column, $value)
    {
        $this->where->greaterThan($column, $value);

        return $this;
    }

    public function greaterThanEqual($column, $value)
    {
        $this->where->greaterThanEqual($column, $value);

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

    public function build(array $params = array()) : Built
    {
        if (is_null($this->table)) {
            throw new \Exception("Insert can not be build, bacause table is not defined.");
        }

        $command = new Built();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        $sql = "UPDATE {$params['quote']}{$this->table}{$params['quote']}";

        $sset = "SET ";

        foreach ($this->values as $key => $value) {
            $column = \Tiie\Data\functions::columnStr($key, $params);
            $s = null;

            if (is_numeric($value)) {
                $s = $value;
            }else if(is_string($value)){
                $uid = $this->getUid();
                $s = ":{$uid}";
                $command->setParam($uid, $value);
            }else if(is_null($value)){
                $s = "null";
            }else{
                throw new \Exception(sprintf("Unsupported type of update value %s", gettype($value)));
            }

            $sset .= "    {$column} = {$s},";
        }

        $sset = trim($sset, ',');

        if (!empty($sset)) {
            $sql .= " $sset";
        }

        $where = $this->where->build($params);

        if (!empty($where->getCommand())) {
            $sql .= "\nWHERE {$where->getCommand()}";
            $command->setParams($where->getParams(), 1);
        }

        $command->setCommand($sql);

        return $command;
    }
}
