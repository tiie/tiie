<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;

class Year implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Tiie.Data.Validator.Year.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
