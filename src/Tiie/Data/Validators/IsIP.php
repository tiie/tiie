<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;

class IsIP extends Validator
{
    public function description()
    {
        return '@(Tiie.Data.Validator.IsIP.Description)';
    }

    public function validate($value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return null;
        } else {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => "@(Tiie.Data.Validator.IsIP.Error)",
            );
        }
    }
}
