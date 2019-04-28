<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class Year extends Validator
{
    public function validate($value)
    {
        trigger_error("Implements Year validator.", E_USER_NOTICE);

        return null;
    }
}
