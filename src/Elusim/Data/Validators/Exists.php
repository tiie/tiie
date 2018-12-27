<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;

class Exists extends Validator
{
    private $field;

    function __construct(string $field)
    {
        $this->field = $field;
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.Exists.Description)';
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => "@(Elusim.Data.Validator.Exists.WrongType)",
            );
        }

        if (!in_array($this->field, array_keys($value))) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                'error' => "@(Elusim.Data.Validator.Exists.Error)",
            );
        }

        return null;
    }
}
