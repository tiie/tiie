<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;

class NotEmpty implements ValidatorInterface
{
    public function description()
    {
        return '@(Elusim.Data.Validators.NotEmpty.Description)';
    }

    public function validate($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    'error' => "@(Elusim.Data.Validators.NotEmpty.IsEmpty)",
                );
            }else{
                return null;
            }
        }elseif(is_string($value)){
            if ($value == "") {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    'error' => "@(Elusim.Data.Validators.NotEmpty.IsEmpty)",
                );
            }else{
                return null;
            }
        }elseif(is_numeric($value)){
            return null;
        }elseif(is_null($value)){
            return array(
                'code' => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                'error' => "@(Elusim.Data.Validators.NotEmpty.IsEmpty)",
            );
        }else{
            if($value instanceof \Countable) {
                if ($value->count() == 0) {
                    return array(
                        'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        'error' => "@(Elusim.Data.Validators.NotEmpty.WrongType)",
                    );
                } else {
                    return null;
                }
            }

            return null;
        }
    }
}
