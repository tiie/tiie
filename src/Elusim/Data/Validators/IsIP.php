<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;

class IsIP implements ValidatorInterface
{
    public function description()
    {
        return '@(Elusim.Data.Validator.IsIP.Description)';
    }

    public function validate($value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return null;
        } else {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => "@(Elusim.Data.Validator.IsIP.Error)",
            );
        }
    }
}
