<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;

class Year extends Validator
{
    public function validate($value)
    {
        trigger_error("Implements Year validator.", E_USER_NOTICE);

        return null;
    }
}
