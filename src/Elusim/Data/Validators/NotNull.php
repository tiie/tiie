<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;

class NotNull implements ValidatorInterface
{
    public function description()
    {
        return '@(Elusim.Data.Validator.NotNull.Description)';
    }

    public function validate($value)
    {
        if (is_null($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Elusim.Data.Validator.NotNull.Invalid)',
            );
        }

        return null;
    }
}
