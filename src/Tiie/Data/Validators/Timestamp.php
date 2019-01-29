<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Validators\Number;

class Timestamp extends Number
{
    function __construct()
    {
        parent::__construct(1);
    }

    public function description()
    {
        return '@(Tiie.Data.Validator.Timestamp.Description)';
    }
}
