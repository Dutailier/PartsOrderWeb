<?php

include_once(ROOT . 'libs/entity.php');

class User extends Entity
{
    private $username;

    function __construct($username)
    {
        $this->username = trim($username);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername()
        );
    }

    public function getUsername()
    {
        return $this->username;
    }
}