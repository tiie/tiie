<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class StringLength extends Validator
{
    private $min;
    private $max;

    function __construct(int $max = null, int $min = 0)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function max(int $max)
    {
        $this->max = $max;

        return $this;
    }

    public function min(int $min)
    {
        $this->min = $min;

        return $this;
    }

    public function validate($value)
    {
        if (!is_string($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        $len = strlen($value);

        if ($len < $this->min) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_LENGTH,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_NOT_REACH_MINIMUM_LENGTH, array("value" => (string) $value)),
            );
        }

        if (!is_null($this->max)) {
            if ($len > $this->max) {
                return array(
                    "code" => ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_LENGTH,
                    "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_LENGTH, array("value" => (string) $value)),
                );
            }
        }

        return null;
    }
}
