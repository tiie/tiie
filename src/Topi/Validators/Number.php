<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Number implements ValidatorInterface
{
    private $unsigned;

    function __construct($unsigned = false)
    {
        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Validators.Number.Description)';
    }

    public function validate($value)
    {
        if ($this->unsigned) {
            preg_match_all('/^([0-9]*)$/', (string)$value, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Number.Invalid)',
                );
            }

            return null;
        }else{
            preg_match_all('/^(-[0-9]*|[0-9]*)$/', (string)$value, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Topi.Validators.Number.Invalid)',
                );
            }

            return null;
        }
    }
}
