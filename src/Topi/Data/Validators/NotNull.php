<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class NotNull implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Data.Validator.NotNull.Description)';
    }

    public function validate($value)
    {
        if (is_null($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validator.NotNull.Invalid)',
            );
        }

        return null;
    }
}
