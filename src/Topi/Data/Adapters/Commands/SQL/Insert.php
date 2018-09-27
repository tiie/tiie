<?php
namespace Topi\Data\Adapters\Commands\SQL;

use Topi\Data\Adapters\Commands\Command;
use Topi\Data\Adapters\Commands\BuiltCommand;

class Insert extends Command
{
    private $table = null;
    private $columns = array();
    private $values = array();

    /**
     * Set table to insert.
     *
     * @param string $table
     * @return $this|string Return set value or $this if is use to set.
     */
    public function into(string $table = null)
    {
        if (!is_null($table)) {
            $this->table = $table;

            return $this;
        }else{
            return $this->table;
        }
    }

    /**
     * @see Insert::into()
     */
    public function table(string $table = null)
    {
        return $this->into($table);
    }

    /**
     * Add value to insert. Value is key array.
     *
     * @param array $value
     * @return $this
     */
    public function value(array $value) : Insert
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * Set values for insert.
     *
     * @param array $values
     * @param int $overwrite
     * @return $this|array
     */
    public function values(array $values = null, int $overwrite = 1)
    {
        if (!is_null($values)) {
            $this->values = $values;

            return $this;
        }else{
            return $this->values;
        }
    }

    /**
     * @see Insert::value()
     */
    public function add($value)
    {
        $this->values[] = $value;

        return $this;
    }

    public function columns($columns = null)
    {
        if (!is_null($columns)) {
            $this->columns = $columns;

            return $this;
        }else{
            return $this->columns;
        }
    }

    public function build(array $params = array())
    {
        if (is_null($this->table)) {
            throw new \InvalidArgumentException("Insert can not be build, bacause table is not defined.");
        }

        $command = new BuiltCommand();

        $params = array_merge(array(
            'quote' => '`'
        ), $params);

        $scolumns = "";
        $svalues = "";

        if (empty($this->columns)) {
            $this->columns = array_keys($this->values[0]);
        }

        if (!empty($this->columns)) {
            foreach ($this->columns as $column) {
                // todo funkcje columnStr trzeba ladniej zapisac
                $scolumns .= sprintf('%s,', \Topi\Data\functions::columnStr($column, $params));
            }

            $scolumns = trim($scolumns, ',');
        }

        if (!empty($this->values)) {
            foreach ($this->values as $row) {
                $t = "";

                foreach ($this->columns as $column) {
                    $value = $row[$column];

                    if (is_numeric($value)) {
                        $t .= "{$value},";
                    }else{
                        $uid = $this->uid();
                        $command->param($uid, $value);
                        $t .= ":{$uid},";
                    }
                }

                $t = trim($t, ',');
                $svalues .= "({$t}),";
            }

            $svalues = trim($svalues, ',');
        }

        $command->command("insert into {$params['quote']}{$this->table}{$params['quote']} ({$scolumns}) values {$svalues}");

        return $command;
    }
}
