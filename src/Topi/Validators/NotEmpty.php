<?php
namespace Topi\Validators;

class NotEmpty implements \Topi\Validators\ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Validators.NotEmpty.Description)';
    }

    public function validate($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                return array(
                    'error' => "@(Topi.Validators.NotEmpty.Error)",
                    'code' => 'isEmpty'
                );
            }else{
                return null;
            }
        }elseif(is_string($value)){
            if ($value == "") {
                return array(
                    'error' => "@(Topi.Validators.NotEmpty.Error)",
                    'code' => 'isEmpty'
                );
            }else{
                return null;
            }
        }elseif(is_numeric($value)){
            return null;
        }elseif(is_null($value)){
            return array(
                'error' => "@(Topi.Validators.NotEmpty.Error)",
                'code' => 'isEmpty'
            );
        }else{
            return array(
                'error' => "@(Topi.Validators.NotEmpty.WrongType)",
                'code' => 'wrongType'
            );
        }
    }
}
