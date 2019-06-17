<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Validators\Number;

class Integer extends Number
{
    private $unsigned;

    function __construct(int $unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function getDescription()
    {
        return '@(Tiie.Data.Validators.Integer.Description)';
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
