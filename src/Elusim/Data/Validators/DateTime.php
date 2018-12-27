<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Validator;

class DateTime extends Validator
{
    private $format;

    function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    public function description()
    {
        return '@(Elusim.Data.Validators.DateTime.Description)';
    }

    public function validate($value)
    {
        $date = \DateTime::createFromFormat($this->format, $value);

        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] + $errors['error_count'] > 0) {
            return array(
                'code' => ValidatorInterface::ERROR_CODE_INVALID,
                'error' => '@(Elusim.Data.Validators.DateTime.Invalid)',
            );
        }

        return null;
    }
}
