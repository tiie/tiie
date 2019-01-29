<?php
namespace Tiie\Exceptions;

class PHPErrorException extends \Exception
{
    function __construct($message, $code, $file, $line)
    {
        parent::__construct($message);

        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }
}
