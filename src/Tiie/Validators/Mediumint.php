<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;

class Mediumint extends Number
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Tiie.Data.Validators.Mediumint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        $value = (int)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 16777215) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Tiie.Data.Validators.Mediumint.Invalid)',
                );
            }
        }else{
            if ($value < -8388608 || $value > 8388607) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Tiie.Data.Validators.Mediumint.Invalid)',
                );
            }
        }

        return null;
    }
}
