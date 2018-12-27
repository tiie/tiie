<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;

class Number extends Validator
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.Number.Description)';
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
                'error' => '@(Elusim.Data.Validator.Number.Invalid)',
            );
        }

        if (is_numeric($value)) {
            if ($this->unsigned) {
                $value = (string) $value;

                if ($value[0] == '-') {
                    return array(
                        'code' => ValidatorInterface::ERROR_CODE_INVALID,
                        'error' => '@(Elusim.Data.Validator.Number.Invalid)',
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
                'error' => '@(Elusim.Data.Validator.Number.Invalid)',
            );
        }
    }
}
