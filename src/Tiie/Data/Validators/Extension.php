<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;

class Extension extends Validator
{
    private $extensions;

    function __construct(array $extensions = array())
    {
        $this->extensions = $extensions;
    }

    public function extensions(array $extensions = array())
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function validate($value)
    {
        if (empty($this->extensions)) {
            trigger_error("There is no any extensions to validate.", E_USER_NOTICE);
        }

        if (!is_string($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (empty($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        $exploded = explode(".", $value);

        if (count($exploded) == 1) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (!in_array(strtolower($exploded[count($exploded)-1]), $this->extensions)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID_EXTENSION,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_INVALID_EXTENSION, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
