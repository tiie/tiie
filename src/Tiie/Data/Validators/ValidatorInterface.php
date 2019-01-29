<?php
namespace Tiie\Data\Validators;

use Tiie\Messages\Helper as MessagesHelper;
use Tiie\Messages\MessagesInterface;

interface ValidatorInterface
{
    const ERROR_CODE_REQUIRED = 'required';
    const ERROR_CODE_INVALID = 'invalid';
    const ERROR_CODE_WRONG_TYPE = 'wrongType';
    const ERROR_CODE_NOT_EXISTS = 'notExists';
    const ERROR_CODE_IS_EMPTY = 'isEmpty';

    public function description();

    public function messages(MessagesInterface $messages = null);

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
