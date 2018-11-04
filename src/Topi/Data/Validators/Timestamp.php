<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;

class Timestamp implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Timestamp.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
