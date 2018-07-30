<?php
namespace Topi\Validators;

class Exists
{
    private $field;

    function __construct(string $field)
    {
        $this->field = $field;
    }

    public function description()
    {
        return '@(Topi.Validators.Exists.Description)';
    }

    public function validate(array $value)
    {
        if (!is_array($value)) {
            return array(
                'error' => "@(Topi.Validators.Exists.WrongType)",
                'code' => 'wrongType'
            );
        }

        if (!in_array($this->field, array_keys($value))) {
            return array(
                'error' => "@(Topi.Validators.Exists.Error)",
                'code' => 'notExists'
            );
        }

        return null;
    }
}
