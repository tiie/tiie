<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class DateTime extends Validator
{
    private $format;

    function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    public function getDescription()
    {
        return '@(Tiie.Data.Validators.DateTime.Description)';
    }

    public function validate($value)
    {
        $date = \DateTime::createFromFormat($this->format, $value);

        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] + $errors['error_count'] > 0) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Tiie.Data.Validators.DateTime.Invalid)',
            );
        }

        return null;
    }
}
