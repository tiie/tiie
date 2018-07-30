<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class NotNull implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Validators.NotNull.Description)';
    }

    public function validate($value)
    {
        if (is_null($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => '@(Topi.Validators.NotNull.WrongType)',
            );
        }

        return null;
    }
}
