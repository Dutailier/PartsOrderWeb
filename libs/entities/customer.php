<?php

include_once(ROOT . 'libs/entity.php');

class Customer extends Entity
{
    const REGEX_PHONE = '/^[1]?[-. ]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
    const REGEX_EMAIL = '/^\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3}$/';

    private $firstname;
    private $lastname;
    private $phone;
    private $email;
    private $addressId;

    function __construct($firstname, $lastname, $phone, $email, $addressId)
    {
        if (!preg_match(Customer::REGEX_PHONE, $phone)) {
            throw new Exception('The phone number must be standard. (i.e. 123-456-7890)');

        } else if (!preg_match(Customer::REGEX_EMAIL, $email)) {
            throw new Exception('The email address must be standard. (i.e. infos@dutailier.com.');
        }

        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = trim($phone);
        $phone = (strlen($phone) == 10 ? '1' : '') . $phone;

        $this->firstname = trim($firstname);
        $this->lastname = trim($lastname);
        $this->phone = $phone;
        $this->email = trim($email);
        $this->addressId = intval($addressId);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'address' => $this->getAddress()->getArray()
        );
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