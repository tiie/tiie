<?php
namespace Tests\Components;

class UsersCategories
{
    private $email;
    private $users;

    public function users(\Tests\Components\Users $users = null)
    {
        if (is_null($users)) {
            return $this->users;
        } else {
            $this->users = $users;

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
