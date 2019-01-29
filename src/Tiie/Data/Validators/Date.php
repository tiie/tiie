<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Validator;

class Date extends Validator
{
    private $format;

    function __construct(string $format = 'Y-m-d')
    {
        $this->format = $format;
    }

    public function description()
    {
        return '@(Tiie.Data.Validators.Date.Description)';
    }

    public function validate($value)
    {
        $date = \DateTime::createFromFormat($this->format, $value);

        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] + $errors['error_count'] > 0) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validators.Date.Invalid)',
            );
        }

        return null;
    }
}
