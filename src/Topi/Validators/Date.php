<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Date implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Validators.Date.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
