<?php
namespace Tiie\Data\Validators;

use Tiie\Messages\Helper as MessagesHelper;
use Tiie\Messages\MessagesInterface;

interface ValidatorInterface
{
    const ERROR_CODE_REQUIRED = 'Required';
    const ERROR_CODE_INVALID = 'Invalid';
    const ERROR_CODE_WRONG_TYPE = 'WrongType';
    const ERROR_CODE_NOT_EXISTS = 'NotExists';
    const ERROR_CODE_IS_EMPTY = 'IsEmpty';

    const ERROR_CODE_EXCEEDS_MAXIMUM_NUMBER_OF_ELEMENTS = "ExceedsMaximumNumberOfElements";
    const ERROR_CODE_NOT_REACH_MINIMUM_NUMBER_OF_ELEMENTS = "NotReachMinimumNumberOfElements";

    const ERROR_CODE_EXCEEDS_MAXIMUM_LENGTH = "ExceedsMaximumLength";
    const ERROR_CODE_NOT_REACH_MINIMUM_LENGTH = "NotReachMinimumLength";

    const ERROR_CODE_EXCEEDS_MAXIMUM_VALUE = "ExceedsMaximumValue";
    const ERROR_CODE_NOT_REACH_MINIMUM_VALUE = "NotReachMinimumValue";

    const ERROR_CODE_INVALID_EXTENSION = "InvalidExtension";

    const ERROR_CODE_FILE_IS_NOT_READABLE = "FileIsNotReadable";
    const ERROR_CODE_FILE_UPLOAD_ERR_INI_SIZE = "FileUploadErrIniSize";
    const ERROR_CODE_FILE_UPLOAD_ERR_FORM_SIZE = "FileUploadErrFormSize";
    const ERROR_CODE_FILE_UPLOAD_ERR_PARTIAL = "FileUploadErrPartial";
    const ERROR_CODE_FILE_UPLOAD_ERR_NO_FILE = "FileUploadErrNoFile";
    const ERROR_CODE_FILE_UPLOAD_ERR_NO_TMP_DIR = "FileUploadErrNoTmpDir";
    const ERROR_CODE_FILE_UPLOAD_ERR_CANT_WRITE = "FileUploadErrCantWrite";
    const ERROR_CODE_FILE_UPLOAD_ERR_EXTENSION = "FileUploadErrExtension";

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
