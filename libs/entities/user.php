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

    public function getStore()
    {
        include_once(ROOT . 'libs/security.php');
        if (!Security::UserIsInRole($this, ROLE_RETAILER)) {
            throw new Exception('The user must be a retailer.');
        }

        include_once(ROOT . 'libs/repositories/stores.php');
        return Stores::FindByUserId($this->getId());
    }
}