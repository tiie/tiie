<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class IsIP extends Validator
{
    public function getDescription()
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
