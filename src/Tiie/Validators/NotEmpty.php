<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class NotEmpty extends Validator
{
    public function validate($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                return array(
                    "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_IS_EMPTY),
                );
            }else{
                return null;
            }
        }elseif(is_string($value)){
            if ($value == "") {
                return array(
                    "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                    "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_IS_EMPTY),
                );
            }else{
                return null;
            }
        }elseif(is_numeric($value)){
            return null;
        }elseif(is_null($value)){
            return array(
                "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_IS_EMPTY),
            );
        }else{
            if($value instanceof \Countable) {
                if ($value->count() == 0) {
                    return array(
                        "code" => ValidatorInterface::ERROR_CODE_IS_EMPTY,
                        "error" => $this->messages()->get(ValidatorInterface::ERROR_CODE_IS_EMPTY),
                    );
                } else {
                    return null;
                }
            }

            return null;
        }
    }
}
