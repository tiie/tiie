<?php
namespace Topi\Data\Validators;

class RowExists implements \Topi\Data\Validators\ValidatorInterface
{
    private $db;
    private $table;
    private $idColumn;

    function __construct($db, $table, $idColumn = 'id')
    {
        $this->db = $db;
        $this->table = $table;
        $this->idColumn = $idColumn;
    }

    public function description()
    {
        return '@(Topi.Data.Validator.RowExists.Description)';
    }

    public function validate($value)
    {
        $select = new \Topi\Data\Adapters\Commands\SQL\Select($this->db);

        $row = $select
            ->from($table)
            ->eq($this->idColumn)
            ->fetch('row')
        ;

        return !is_null($row);
    }
}
