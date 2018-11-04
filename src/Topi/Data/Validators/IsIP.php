<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class IsIP implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Data.Validator.IsIP.Description)';
    }

    public function validate($value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return null;
        } else {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => "@(Topi.Data.Validator.IsIP.Error)",
            );
        }
    }
}
