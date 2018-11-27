<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Number;

class Time implements Number
{
    function __construct()
    {
        parent::__construct(1);
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.Time.Description)';
    }
}