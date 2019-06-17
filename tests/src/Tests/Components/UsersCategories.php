<?php
namespace Tests\Components;

use Tests\Components\Email;
use Tests\Components\Users;

class UsersCategories
{
    private $email;
    private $users;

    public function setUsers(Users $users) : void
    {
        $this->users = $users;
    }

    public function getUsers() : ?Users
    {
        return $this->users;
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
