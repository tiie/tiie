<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;

class Timestamp extends Number
{
    function __construct()
    {
        parent::__construct(1);
    }

    public function getDescription()
    {
        return '@(Tiie.Data.Validator.Timestamp.Description)';
    }
}
