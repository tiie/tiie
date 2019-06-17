<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class NotNull extends Validator
{
    public function getDescription()
    {
        return '@(Tiie.Data.Validator.NotNull.Description)';
    }

    public function validate($value)
    {
        if (is_null($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validator.NotNull.Invalid)',
            );
        }

        return null;
    }
}
