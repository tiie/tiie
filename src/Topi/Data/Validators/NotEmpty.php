<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class NotEmpty implements ValidatorInterface
{
    public function description()
    {
        return '@(Topi.Data.Validator.NotEmpty.Description)';
    }

    public function validate($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    'error' => "@(Topi.Data.Validator.NotEmpty.IsEmpty)",
                );
            }else{
                return null;
            }
        }elseif(is_string($value)){
            if ($value == "") {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    'error' => "@(Topi.Data.Validator.NotEmpty.IsEmpty)",
                );
            }else{
                return null;
            }
        }elseif(is_numeric($value)){
            return null;
        }elseif(is_null($value)){
            return array(
                'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                'error' => "@(Topi.Data.Validator.NotEmpty.IsEmpty)",
            );
        }else{
            if($value instanceof \Countable) {
                if ($value->count() == 0) {
                    return array(
                        'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        'error' => "@(Topi.Data.Validator.NotEmpty.WrongType)",
                    );
                } else {
                    return null;
                }
            }

            return null;
        }
    }
}
