<?php
namespace Tiie\Validators;

use Tiie\Validators\Number;

class Bigint extends Number
{
    private $unsigned;

    function __construct($unsigned = 0)
    {
        parent::__construct($unsigned);

        $this->unsigned = $unsigned;
    }

    public function description()
    {
        return '@(Tiie.Data.Validators.Bigint.Description)';
    }

    public function validate($value)
    {
        if(!is_null($errors = parent::validate($value))){
            return $errors;
        }

        // todo Bigint validate.
        return null;
    }
}
