<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;
use Topi\Data\Validators\Number;

class Email implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Data.Validators.Email.Description)';
    }

    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }else{
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validators.Email.Invalid)',
            );
        }
    }
}
