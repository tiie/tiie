<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Number;

class Email implements ValidatorInterface
{
    public function description()
    {
        return '@(Elusim.Data.Validators.Email.Description)';
    }

    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }else{
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Elusim.Data.Validators.Email.Invalid)',
            );
        }
    }
}
