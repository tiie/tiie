<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Year implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Validators.Year.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
