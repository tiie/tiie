<?php
namespace Elusim\Data\Validators;

use Elusim\Data\Validators\ValidatorInterface;
use Elusim\Data\Validators\Number;

class Timestamp extends Number
{
    function __construct()
    {
        parent::__construct(1);
    }

    public function description()
    {
        return '@(Elusim.Data.Validator.Timestamp.Description)';
    }
}
