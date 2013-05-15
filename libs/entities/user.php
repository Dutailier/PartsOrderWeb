<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class User extends Entity
{
    private $id;
    private $username;

    function __construct($id, $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
}