<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class FileReadable extends Validator
{
    public function validate($value)
    {
        if (!is_string($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if (empty($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        $exploded = explode(".", $value);

        if (!is_readable($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_IS_NOT_READABLE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_IS_NOT_READABLE, array("value" => (string) $value)),
            );
        }

        return null;
    }
}
