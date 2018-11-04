<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class Year implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Year.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
