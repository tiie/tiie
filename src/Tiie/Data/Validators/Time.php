<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Number;
use Tiie\Data\Validators\Validator;

class Time extends Validator
{
    public function validate($value)
    {
        trigger_error("Implements Time validator.", E_USER_NOTICE);

        return null;
    }
}
