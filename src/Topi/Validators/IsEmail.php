<?php
namespace Topi\Validators;

class IsEmail implements \Topi\Validators\ValidatorInterface
{
    public function description()
    {
        return array(
            'pl' => 'Sprawdza czy wartość jest emailem.'
        );
    }

    public function error($value)
    {
        return array(
            // 'pl' => sprintf('%s jest wymagane.', $name),
            'en-US,en' => sprintf('%s is not email', $value)
        );
    }

    public function errorCode()
    {
        return 'notEmail';
    }

    public function isValid($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
