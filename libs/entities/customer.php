<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Customer extends Entity
{
    const REGEX_PHONE = '/^[1]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
    const REGEX_EMAIL = '/^\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3}$/';

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

    public function getArray($deep = false)
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

    public function getAddress()
    {
        include_once(ROOT . 'libs/repositories/addresses.php');

        return Addresses::Find($this->getAddressId());
    }
}