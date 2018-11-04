<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class Enum implements ValidatorInterface
{
    private $enum;
    private $hasObject = 0;

    function __construct(array $enum = array())
    {
        $this->enum = $enum;

        foreach ($this->enum as $value) {
            if (is_object($value)) {
                $this->hasObject = 1;
                break;
            }
        }
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Enum.Description)';
    }

    public function validate($value)
    {
        $found = 0;

        foreach($this->enum as $enumValue) {
            if (is_object($enumValue) || is_object($value)) {
                if ($enumValue === $value) {
                    $found = 1;
                    break;
                }
            } else {
                if ($enumValue == $value) {
                    $found = 1;
                    break;
                }
            }
        }

        if ($found) {
            return null;
        } else {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validator.Enum.Invalid)',
            );
        }
    }
}
