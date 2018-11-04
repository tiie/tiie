<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class DateTime implements ValidatorInterface
{
    private $format;

    function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    public function description()
    {
        return '@(Topi.Data.Validators.DateTime.Description)';
    }

    public function validate($value)
    {
        $date = \DateTime::createFromFormat($this->format, $value);

        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] + $errors['error_count'] > 0) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Topi.Data.Validators.DateTime.Invalid)',
            );
        }

        return null;
    }
}
