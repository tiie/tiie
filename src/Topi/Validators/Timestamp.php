<?php
namespace Topi\Validators;

use Topi\Validators\ValidatorInterface;

class Timestamp implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Topi.Validators.Timestamp.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
