<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');
include_once(ROOT . 'libs/repositories/users.php');

/**
 * Class Retailer
 * Représente un détaillant.
 */
class Retailer implements IComparable
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
        $this->name = trim($name);
        $this->email = trim($email);
        $this->addressId = $addressId;

        // Retire les caractères autres que les chiffres.
        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = (strlen($phone) == 10 ? '1' : '') + $phone;
        $this->phone = $phone;
    }

    public function equals($object)
    {
        return
            $object instanceof self &&
            $object->getId() == $this->getId();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'name' => $this->getName(),
            'phone' => (string)$this->getPhone(),
            'email' => $this->getEmail(),
            'addressId' => $this->getAddressId()
        );
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

    public function getAddress()
    {
        return Addresses::Find($this->addressId);
    }

    public function getUser()
    {
        return Users::Find($this->userId);
    }
}