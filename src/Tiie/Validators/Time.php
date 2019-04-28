<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;
use Tiie\Validators\Validator;

class Time extends Validator
{
    public function validate($value)
    {
        trigger_error("Implements Time validator.", E_USER_NOTICE);

        return null;
    }
}
