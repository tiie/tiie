<?php
namespace Tiie\Data\Adapters\Commands\SQL;

use Tiie\Data\Adapters\Commands\Command;
use Tiie\Data\Adapters\Commands\Built;

/**
 *
 * ```php
 * $insert = new Insert($this->getAdapter('bookshop'));
 *
 * $insert->into('users')
 *     ->add(array(
 *         'id' => 3000,
 *         'firstName' => 'Illustrée',
 *         'lastName' => 'Sauvage',
 *         'email' => 'jsauvagei@parallels.com',
 *         'genderId' => 257,
 *         'birthDate' => '0000-00-00',
 *         'ip' => '152.106.13.28',
 *         'countryId' => NULL,
 *         'cityId' => 1134,
 *         'phone' => '501-972-3966',
 *     ))
 *     ->execute()
 * ;
 * ```
 */
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
    public function setTable(string $table = null) : void
    {
        $this->into($table);
    }

    /**
     * Add value to insert. Value is key array.
     *
     * ```php
     * $insert = new Insert();
     *
     * $insert->addValue(array(
     *     'id' => 1,
     *     'name' => 'Pawel'
     * ));
     *
     * $insert->addValue(array(
     *     'id' => 2,
     *     'name' => 'Pawel'
     * ));
     * ```
     *
     * @param array $value
     * @return $this
     */
    public function addValue(array $value) : void
    {
        $this->values[] = $value;
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
     * @see Insert::value()
     */
    public function add($value)
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * Set columns for INSERT statement. If columns is not set, then first
     * column from values are used.
     *
     * ```php
     * $insert = new Insert();
     * $insert->columns(array('id', 'name'));
     * ```
     *
     * @param array $columns
     * @return $this|array
     */
    public function columns(array $columns = null)
    {
        if (!is_null($columns)) {
            $this->columns = $columns;

            return $this;
        }else{
            return $this->columns;
        }
    }

    public function build(array $params = array()) : Built
    {
        if (is_null($this->table)) {
            throw new \InvalidArgumentException("Insert can not be build, bacause table is not defined.");
        }

        $command = new Built();

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
                $scolumns .= sprintf('%s,', \Tiie\Data\functions::columnStr($column, $params));
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
                        $uid = $this->getUid();
                        $command->setParam($uid, $value);
                        $t .= ":{$uid},";
                    }
                }

                $t = trim($t, ',');
                $svalues .= "({$t}),";
            }

            $svalues = trim($svalues, ',');
        }

        $command->setCommand("insert into {$params['quote']}{$this->table}{$params['quote']} ({$scolumns}) values {$svalues}");

        return $command;
    }
}
