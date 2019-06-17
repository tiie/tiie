<?php declare(strict_types=1);

namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;
use Tiie\Validators\Validator;

class MinNumberOfElements extends Validator
{
    private $min;

    function __construct(int $min = null)
    {
        $this->min = $min;
    }

    public function min(int $min)
    {
        $this->min = $min;

        return $this;
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (is_null($this->min)) {
            trigger_error("Min number of elements is not defined.", E_USER_NOTICE);

            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if ($value <  $this->min) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_NUMBER_OF_ELEMENTS,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_NUMBER_OF_ELEMENTS, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
