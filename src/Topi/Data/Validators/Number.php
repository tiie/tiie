<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class Number implements ValidatorInterface
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Number.Description)';
    }

    public function validate($value)
    {
        if (
               is_null($value)
            || is_array($value)
            || is_object($value)
        ) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validator.Number.Invalid)',
            );
        }

        if (is_numeric($value)) {
            if ($this->unsigned) {
                if ($value < 0) {
                    return array(
                        'code' => ValidatorInterface::ERROR_CODE_INVALID,
                        'error' => '@(Topi.Data.Validator.Number.Invalid)',
                    );
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validator.Number.Invalid)',
            );
        }
    }
}
