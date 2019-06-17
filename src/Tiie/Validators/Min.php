<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;
use Tiie\Validators\Validator;

class Min extends Validator
{
    private $min;

    function __construct(string $min = null)
    {
        $this->min = $min;
    }

    public function min(string $min)
    {
        $this->min = $min;

        return $this;
    }

    public function validate($value)
    {
        if (!is_numeric($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (is_null($this->min)) {
            trigger_error("Min value is not defined.", E_USER_NOTICE);

            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if ($value <  $this->min) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_VALUE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_VALUE, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
