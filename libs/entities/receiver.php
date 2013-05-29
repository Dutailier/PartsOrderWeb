<?php

include_once(ROOT . 'libs/entity.php');

class Receiver extends Entity
{
    const REGEX_PHONE = '/^[1]?[-. ]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
    const REGEX_EMAIL = '/^\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3}$/';

    private $name;
    private $phone;
    private $email;

    function __construct($name, $phone, $email)
    {
        if (!preg_match(Receiver::REGEX_PHONE, $phone)) {
            throw new Exception('The phone number must be standard. (i.e. 123-456-7890)');

        } else if (!preg_match(Receiver::REGEX_EMAIL, $email)) {
            throw new Exception('The email address must be standard. (i.e. infos@dutailier.com.');
        }

        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = trim($phone);
        $phone = (strlen($phone) == 10 ? '1' : '') . $phone;

        $this->name = trim($name);
        $this->phone = $phone;
        $this->email = trim($email);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail()
        );
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
}