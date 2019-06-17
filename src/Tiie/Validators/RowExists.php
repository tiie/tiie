<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;
use Tiie\Data\Adapters\AdapterInterface;
use Tiie\Data\Adapters\Commands\SQL\Select;

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

    public function getDescription()
    {
        return '@(Tiie.Data.Validator.RowExists.Description)';
    }

    public function validate($value)
    {
        $select = new Select($this->db);

        $row = $select
            ->from($this->container)
            ->equal($this->fieldId, $value)
            ->fetch()->format('row')
        ;

        if (is_null($row)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validator.Number.Invalid)',
            );
        } else {
            return null;
        }
    }
}
