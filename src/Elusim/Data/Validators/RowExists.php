<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;
use Elusim\Data\Adapters\AdapterInterface;
use Elusim\Data\Adapters\Commands\SQL\Select;

class RowExists extends Validator
{
    private $db;
    private $container;
    private $fieldId;

    function __construct(AdapterInterface $db, string $container, string $fieldId = 'id')
    {
        $this->db = $db;
        $this->container = $container;
        $this->fieldId = $fieldId;
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.RowExists.Description)';
    }

    public function validate($value)
    {
        $select = new Select($this->db);

        $row = $select
            ->from($this->container)
            ->eq($this->fieldId, $value)
            ->fetch()->format('row')
        ;

        if (is_null($row)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Elusim.Data.Validator.Number.Invalid)',
            );
        } else {
            return null;
        }
    }
}
