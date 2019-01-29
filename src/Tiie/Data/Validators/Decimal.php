<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;
use Tiie\Data\Validators\Number;

class Decimal extends Validator
{
    private $length;
    private $decimals;

    function __construct($length = 10, $decimals = 2)
    {
        $this->length = $length;
        $this->decimals = $decimals;

        if (is_null($length) || is_null($decimals)) {
            throw new \Exception("length and decimals can not be null.");
        }
    }

    public function description()
    {
        return '@(Tiie.Data.Validator.Decimal.Description)';
    }

    public function validate($value)
    {
        $value = (string)$value;

        preg_match_all('/^([0-9]*|[0-9]*\.[0-9]*|-[0-9]*|-[0-9]*\.[0-9]*)$/', $value, $matches, PREG_SET_ORDER, 0);

        if (empty($matches)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validator.Decimal.Invalid)',
            );
        }

        $value = explode('.', $value);

        if ($value[0][0] === '-') {
            $value[0] = substr($value[0], 1);
        }

        if (strlen($value[0]) > $this->length - $this->decimals) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validator.Decimal.Invalid)',
            );
        }

        if (isset($value[1])) {
            if (strlen($value[1]) > $this->decimals) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Tiie.Data.Validator.Decimal.Invalid)',
                );
            }
        }

        return null;
    }
}
