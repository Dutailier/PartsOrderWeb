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
        $this->id = $id;
        $this->firstname = trim($firstname);
        $this->lastname = trim($lastname);
        $this->email = trim($email);
        $this->addressId = $addressId;

        // Retire les caractères autres que les chiffres.
        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = (strlen($phone) == 10 ? '1' : '') + $phone;
        $this->phone = $phone;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'phone' => (string)$this->getPhone(),
            'email' => $this->getEmail(),
            'addressId' => $this->getAddressId(),
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

    public function getAddress()
    {
        return Addresses::Find($this->addressId);
    }
}