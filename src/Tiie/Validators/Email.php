<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;
use Tiie\Validators\Number;

class Email extends Validator
{
    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }else{
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }
    }
}
