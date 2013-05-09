<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/addresses.php');

/**
 * Class Customer
 * Représente un client.
 */
class Customer
{
    private $id;
    private $firstname;
    private $lastname;
    private $phone;
    private $email;
    private $addressId;

    public function __construct($id, $firstname, $lastname, $phone, $email, $addressId)
    {
        $this->id = trim($id);
        $this->firstname = trim($firstname);
        $this->lastname = trim($lastname);
        $this->email = trim($email);
        $this->addressId = $addressId;

        // Retire les caractères autres que les chiffres.
        $this->phone = preg_replace('/[^\d]/', '', $phone);
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getAddress()
    {
        return Addresses::Find($this->addressId);
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastname()
    {
        return $this->lastname;
    }
}