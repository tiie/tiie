<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;

class NotNull extends Validator
{
    public function description()
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
