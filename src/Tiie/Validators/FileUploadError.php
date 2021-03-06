<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Validator;

class FileUploadError extends Validator
{
    public function validate($value)
    {
        if (!is_numeric($value)) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_INVALID,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_INVALID, array("value" => (string) $value)),
            );
        }

        if ($value == UPLOAD_ERR_OK) {
            return null;
        }

        if($value == UPLOAD_ERR_INI_SIZE) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_INI_SIZE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_INI_SIZE, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_FORM_SIZE) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_FORM_SIZE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_FORM_SIZE, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_PARTIAL) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_PARTIAL,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_PARTIAL, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_NO_FILE) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_NO_FILE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_NO_FILE, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_NO_TMP_DIR) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_NO_TMP_DIR,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_NO_TMP_DIR, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_CANT_WRITE) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_CANT_WRITE,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_CANT_WRITE, array("value" => (string) $value))
            );
        }

        if($value == UPLOAD_ERR_EXTENSION) {
            return array(
                "code" => ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_EXTENSION,
                "error" => $this->getMessages()->get(ValidatorInterface::ERROR_CODE_FILE_UPLOAD_ERR_EXTENSION, array("value" => (string) $value))
            );
        }

        return null;
    }
}
