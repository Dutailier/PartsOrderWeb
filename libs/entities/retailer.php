<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Retailer extends Entity
{
    const REGEX_PHONE = '^[1]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$';
    const REGEX_EMAIL = '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$';
    private $id;
    private $userId;
    private $name;
    private $phone;
    private $email;
    private $addressId;

    function __construct($id, $userId, $name, $phone, $email, $addressId)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->addressId = $addressId;
    }

    public function getArray($deep = false)
    {
        return array(
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'addressId' => $this->getAddressId()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getUser()
    {
        include_once(ROOT . 'libs/repositories/users.php');

        return Users::Find($this->getUserId());
    }

    public function getAddress()
    {
        include_once(ROOT . 'libs/repositories/addresses.php');

        return Addresses::Find($this->getAddressId());
    }
}