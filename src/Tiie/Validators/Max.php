<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;
use Tiie\Validators\Validator;

class Max extends Validator
{
    private $max;

    function __construct(string $max = null)
    {
        $this->max = $max;
    }

    public function max(string $max)
    {
        $this->max = $max;

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

        if (is_null($this->max)) {
            trigger_error("Max value is not defined.", E_USER_NOTICE);

            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if ($value > $this->max) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_VALUE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_VALUE, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
