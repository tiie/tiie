<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;
use Elusim\Data\Validators\Number;

class Email extends Validator
{
    public function validate($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }else{
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }
    }
}
