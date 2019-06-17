<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;
use Tiie\Validators\ComplexValidatorInterface;

class ImageSize extends Validator implements ComplexValidatorInterface
{
    private $max;
    private $min;

    function __construct(string $max = null, string $min = null)
    {
        $this->max = $max;
        $this->min = $min;
    }

    public function validate($value)
    {
        $max = $this->max;
        $min = $this->min;

        if (!is_null($max)) {
            $max = intval($max);

            if ($max === 0) {
                trigger_error("Invalid value of max size of image.", E_USER_NOTICE);

                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
                );
            }
        }

        $errors = array();

        if (!is_null($min)) {
            $min = intval($min);

            if ($min === 0) {
                trigger_error("Invalid value of min size of image.", E_USER_NOTICE);

                $errors[] = array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
                );
            }
        }

        if(is_array($value)) {
            if (
                array_key_exists("name", $value) &&
                array_key_exists("type", $value) &&
                array_key_exists("tmp_name", $value) &&
                array_key_exists("error", $value) &&
                array_key_exists("size", $value)
            ) {
                // It is standard PHP upload.
                if (!is_null($min) && (intval($value["size"]) < $min)) {

                }
            } else{
                trigger_error("Unsupported type of image.", E_USER_NOTICE);

                return null;
            }
        } else if(is_string($value)) {

        } else {
            trigger_error("Unsupported type of image.", E_USER_NOTICE);

            return null;
        }

        // if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
        //     return null;
        // }else{
        //     return array(
        //         'code' => ValidatorInterface::ERROR_CODE_INVALID,
        //         'error' => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
        //     );
        // }
    }
}
