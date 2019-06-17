<?php
namespace Tiie\Exceptions\Http;

class Base extends \Exception
{
    private $errors;
    protected $code;

    public function __construct($errors = null, $code, \Exception $previous = null)
    {
        parent::__construct("Http error", 0, $previous);

        if (is_string($errors)) {
            $errors = array('message' => $errors);
        }

        $this->errors = $errors;
        $this->code = $code;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getHttpCode()
    {
        return $this->code;
    }
}

