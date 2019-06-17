<?php
namespace Tests\Components;

use Tests\Components\UsersCategories;
use Tests\Components\Email;

class Users
{
    private $email;
    private $categories;

    function __construct(\Tests\Components\Email $email)
    {
        $this->email = $email;
    }

    public function setCategories(UsersCategories $categories) : void
    {
        $this->categories = $categories;
    }

    public function getCategories() : ?UsersCategories
    {
        return $this->categories;
    }

    public function setEmail(Email $email) : void
    {
        $this->email = $email;
    }

    public function getEmail() : Email
    {
        return $this->email;
    }
}
