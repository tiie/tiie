<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;
use Topi\Validators\Number;

class Email implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Validators.Email.Description)';
    }

    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }else{
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Validators.Email.Invalid)',
            );
        }
    }
}
