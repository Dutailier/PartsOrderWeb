<?php

include_once('config.php');

class Retailer {
    private $id;
    private $name;
    private $phone;
    private $email;
    private $addressId;

    function __construct($id, $name, $phone, $email, $addressId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->addressId = $addressId;
    }

    public function getId()
    {
        return $this->id;
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
}