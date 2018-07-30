<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Enum implements ValidatorInterface
{
    private $enum;

    function __construct(array $enum)
    {
        $this->enum = $enum;
    }

    public function description()
    {
        return '@(Topi.Validators.Enum.Description)';
    }

    public function validate($value)
    {
        if (!in_array($value, $this->enum)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Validators.StringLength.Invalid)',
            );
        }

        return null;
    }
}
