<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class DateTime implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Validators.DateTime.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
