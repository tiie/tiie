<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class StringLength implements ValidatorInterface
{
    private $min;
    private $max;

    function __construct($max = null, $min = 0)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function description()
    {
        return '@(Topi.Validators.StringLength.Description)';
    }

    public function validate($value)
    {
        if (is_string($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => '@(Topi.Validators.StringLength.WrongType)',
            );
        }

        $len = strlen($value);

        if ($len < $this->min) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Validators.StringLength.Invalid)',
            );
        }

        if (!is_null($this->max)) {
            if ($len > $this->max) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.StringLength.Invalid)',
                );
            }
        }

        return null;
    }
}
