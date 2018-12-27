<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;

class StringLength extends Validator
{
    private $min;
    private $max;

    function __construct(int $max = null, int $min = 0)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.StringLength.Description)';
    }

    public function validate($value)
    {
        if (!is_string($value)) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                'error' => '@(Elusim.Data.Validator.StringLength.WrongType)',
            );
        }

        // TODO StringLength unicode support
        // Dorobić wsparcie dla znaków Unicode.

        $len = strlen($value);

        if ($len < $this->min) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Elusim.Data.Validator.StringLength.Invalid)',
            );
        }

        if (!is_null($this->max)) {
            if ($len > $this->max) {
                return array(
                    'code' => ValidatorInterface::ERROR_CODE_INVALID,
                    'error' => '@(Elusim.Data.Validator.StringLength.Invalid)',
                );
            }
        }

        return null;
    }
}
