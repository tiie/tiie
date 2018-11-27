<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;

class Year implements ValidatorInterface
{
    function __construct()
    {
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.Year.Description)';
    }

    public function validate($value)
    {
        // todo
        throw new \Exception("Dorobic");
    }
}
