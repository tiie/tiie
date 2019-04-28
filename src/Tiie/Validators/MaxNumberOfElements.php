<?php declare(strict_types=1);

namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;
use Tiie\Validators\Validator;

class MaxNumberOfElements extends Validator
{
    private $max;

    function __construct(int $max = null)
    {
        $this->max = $max;
    }

    public function max(int $max)
    {
        $this->max = $max;

        return $this;
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (is_null($this->max)) {
            trigger_error("Max number of elements is not defined.", E_USER_NOTICE);

            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (count($value) > $this->max) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_NUMBER_OF_ELEMENTS,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_NUMBER_OF_ELEMENTS, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
