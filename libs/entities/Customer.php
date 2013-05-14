<?php

include_once('config.php');

class Customer
{
    private $id;
    private $firstname;
    private $lastname;
    private $phone;
    private $email;
    private $addressId;

    function __construct($id, $firstname, $lastname, $phone, $email, $addressId)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->email = $email;
        $this->addressId = $addressId;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'addressId' => $this->getAddressId()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
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
}