<?php
namespace Topi\Data\Validators;

use Topi\Data\Validators\ValidatorInterface;
use Topi\Data\Validators\Number;

class Integer extends Number
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Topi.Data.Validators.Integer.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        // todo Integer validate.
        return null;
    }
}
