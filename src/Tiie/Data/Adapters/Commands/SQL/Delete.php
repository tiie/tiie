<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\SQL\Where;
use Tiie\Data\Adapters\Commands\Built;

class Delete extends \Tiie\Data\Adapters\Commands\Command
{
    private $from = null;
    private $where;

    function __construct(\Tiie\Data\Adapters\AdapterInterface $adapter = null)
    {
        parent::__construct($adapter);

        $this->where = new Where();
    }

    public function from($from = null)
    {
        if (!is_null($from)) {
            $this->from = $from;

            return $this;
        }else{
            return $this->from;
        }
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

    public function eq($column, $value)
    {
        // todo Remove Delete::eq()
        trigger_error("Remove Delete::eq().", E_USER_NOTICE);

        return $this->equal($column, $value);
    }

    public function neq($column, $value)
    {
        // todo Remove Delete::neq()
        trigger_error("Remove Delete::neq().", E_USER_NOTICE);

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

    public function setParams(array $values, array $fields = array()) : void
    {
        $this->where->setParams($values, $fields);
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

    public function notBetween($column, $begin, $end)
    {
        $this->where->notBetween($column, $begin, $end);

        return $this;
    }

    public function build(array $params = array()) : Built
    {
        if (is_null($this->from)) {
            throw new \Exception("Delete can not be build. From is not defined.");
        }

        $command = new \Tiie\Data\Adapters\Commands\Built();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        // sql
        $sql = "delete from {$params['quote']}{$this->from}{$params['quote']}";

        // where
        $where = $this->where->build($params);

        if (!is_null($where->getCommand())) {
            $sql .= " WHERE {$where->getCommand()}";
            $command->setParams($where->getParams(), 1);
        }

        $command->setCommand($sql);

        return $command;
    }
}
