<?php
namespace Elusim\Data\Adapters\Commands\SQL;

use Elusim\Data\Adapters\Commands\Command;
use Elusim\Data\Adapters\Commands\BuiltCommand;

/**
 *
 * ```php
 * $insert = new Insert($this->adapter('bookshop'));
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
    public function table(string $table = null)
    {
        return $this->into($table);
    }

    /**
     * Add value to insert. Value is key array.
     *
     * ```php
     * $insert = new Insert();
     *
     * $insert->value(array(
     *     'id' => 1,
     *     'name' => 'Pawel'
     * ));
     *
     * $insert->value(array(
     *     'id' => 2,
     *     'name' => 'Pawel'
     * ));
     * ```
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
                $scolumns .= sprintf('%s,', \Elusim\Data\functions::columnStr($column, $params));
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
