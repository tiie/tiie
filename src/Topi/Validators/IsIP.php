<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class IsIP implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Validators.IsIP.Description)';
    }

    public function validate($value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return null;
        } else {
            return array(
                'error' => "@(Topi.Validators.IsIP.Error)",
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
            );
        }
    }
}
