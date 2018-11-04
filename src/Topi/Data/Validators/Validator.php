<?php
namespace Topi\Data\Validators;

class Validator
{
    private $fields = array();
    private $validators;

    public function validator($field, $validator)
    {
        if (!isset($this->validators[$field])) {
            $this->validators[$field] = array();
        }

        $this->validators[$field][] = $validator;

        return $this;
    }

    public function prepare($data)
    {
    }

    public function validate($data, $params = array())
    {
        $errors = array();

        // $params = array_replace(array(
        //     'unset'

        // ), $params);

        foreach ($data as $name => $value) {
        }

        foreach ($this->validators as $field => $validators) {
            foreach ($validators as $name => $validator) {
                if ($validator instanceof \Topi\Data\Validators\ValidatorInterface) {
                    // if ($error === $validator->validate($) {
                    //
                    // }

                }elseif(is_string($validator)){

                }else{
                    throw new \Exception("Unsuported validator type.");
                }
            }
        }
    }
}
