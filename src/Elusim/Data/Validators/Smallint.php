<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Number;

class Smallint extends Number
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Elusim.Data.Validators.Smallint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        $value = (int)$value;

        if ($this->unsigned) {
            if ($value < 0 || $value > 65535) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Elusim.Data.Validators.Smallint.Invalid)',
                );
            }
        }else{
            if ($value < -32768 || $value > 32767) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Elusim.Data.Validators.Smallint.Invalid)',
                );
            }
        }

        return null;
    }
}
