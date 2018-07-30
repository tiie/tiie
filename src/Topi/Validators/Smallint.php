<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;
use Topi\Validators\Number;

class Smallint extends Number
{
    private $unsigned;

    function __construct($unsigned = false)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Validators.Smallint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        $value = (string)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 65535) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Smallint.Invalid)',
                );
            }
        }else{
            if ($value < -32768 || $value > 32767) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Smallint.Invalid)',
                );
            }
        }

        return null;
    }
}
