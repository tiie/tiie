<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;
use Topi\Data\Validators\Number;

class Tinyint extends Number
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Data.Validators.Tinyint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        $value = (string)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 255) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Data.Validators.Tinyint.Invalid)',
                );
            }
        }else{
            if ($value < -128 || $value > 127) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Data.Validators.Tinyint.Invalid)',
                );
            }
        }

        return null;
    }
}
