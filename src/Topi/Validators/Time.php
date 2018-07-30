<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Time implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Validators.Time.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
