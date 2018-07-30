<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;
use Topi\Validators\Number;

class Tinyint extends Number
{
    private $unsigned;

    function __construct($unsigned = false)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Validators.Tinyint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        // todo trzeba stworzyc inna metode sprawdzenia. To jest za duza liczba
        // i zapisywana jest zmienno przecinkowo przez co porownanie jest
        // zafalszowane

        $value = (string)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 18446744073709551615) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Tinyint.Invalid)',
                );
            }
        }else{
            if ($value < -9223372036854775808 || $value > 9223372036854775807) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Tinyint.Invalid)',
                );
            }
        }

        return null;
    }
}
