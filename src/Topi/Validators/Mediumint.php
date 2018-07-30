<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;
use Topi\Validators\Number;

class Mediumint extends Number
{
    private $unsigned;

    function __construct($unsigned = false)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Validators.Mediumint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        $value = (string)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 16777215) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Mediumint.Invalid)',
                );
            }
        }else{
            if ($value < -8388608 || $value > 8388607) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Mediumint.Invalid)',
                );
            }
        }

        return null;
    }
}
