<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/users.php');

/**
 * Class Retailer
 * Représente un détaillant.
 */
class Retailer
{
    private $id;
    private $userId;
    private $name;
    private $phone;
    private $email;
    private $addressId;

    public function __construct($id, $userId, $name, $phone, $email, $addressId)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->addressId = $addressId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getAddress()
    {
        return Addresses::Find($this->addressId);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUser()
    {
        return Users::Find($this->userId);
    }
}