<?php
namespace Topi\Validators;

class IsNumeric implements \Topi\Validators\ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Validators.IsNumeric.Description)';
    }

    public function validate($value)
    {
        if (is_numeric($value)) {
            return null;
        }else{
            return array(
                'error' => "@(Topi.Validators.IsNumeric.Error)",
                'code' => 'notNumeric'
            );
        }
    }
}
