<?php
namespace Tests\Components;

class Users
{
    private $email;
    private $categories;

    function __construct(\Tests\Components\Email $email)
    {
        $this->email = $email;
    }

    public function categories(\Tests\Components\UsersCategories $categories = null)
    {
        if (is_null($categories)) {
            return $this->categories;
        } else {
            $this->categories = $categories;

            return $this;
        }
    }

    public function email(\Tests\Components\Email $email = null)
    {
        if (is_null($email)) {
            return $this->email;
        } else {
            $this->email = $email;

            return $this;
        }
    }
}
