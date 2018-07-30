<?php
namespace Topi\Validators;

interface ValidatorInterface
{
    const ERROR_CODE_REQUIRED = 'required';
    const ERROR_CODE_INVALID = 'invalid';
    const ERROR_CODE_WRONG_TYPE = 'wrongType';

    public function description();

    /**
     * Check if given value is valid. If jest then null value should be
     * return. If not then array with information what's is wrong.
     *
     * @param mixed $value
     * @return null|array Return result of validation. null means that value is
     * corrent. Array is return when value isn't correct. Array contains : error, errorCode indexs.
     */
    public function validate($value);
}
