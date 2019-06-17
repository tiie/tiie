<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class Exists extends Validator
{
    private $field;

    function __construct(string $field)
    {
        $this->field = $field;
    }

    public function getDescription()
    {
        return '@(Tiie.Data.Validator.Exists.Description)';
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => "@(Tiie.Data.Validator.Exists.WrongType)",
            );
        }

        if (!in_array($this->field, array_keys($value))) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                'error' => "@(Tiie.Data.Validator.Exists.Error)",
            );
        }

        return null;
    }
}
