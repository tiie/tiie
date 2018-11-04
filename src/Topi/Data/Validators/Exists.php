<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class Exists
{
    private $field;

    function __construct(string $field)
    {
        $this->field = $field;
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Exists.Description)';
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => "@(Topi.Data.Validator.Exists.WrongType)",
            );
        }

        if (!in_array($this->field, array_keys($value))) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                'error' => "@(Topi.Data.Validator.Exists.Error)",
            );
        }

        return null;
    }
}
