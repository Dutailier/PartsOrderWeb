<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/orders.php');

/**
 * Class Store
 * Représente un magasin.
 */
class Store extends Entity
{
    const REGEX_PHONE = '/^[1]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
    const REGEX_EMAIL = '/^\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3}$/';
    private $bannerId;
    private $userId;
    private $name;
    private $phone;
    private $email;
    private $addressId;

    /**
     * Initialise un magasin.
     * @param $bannerId
     * @param $userId
     * @param $name
     * @param $phone
     * @param $email
     * @param $addressId
     */
    function __construct($bannerId, $userId, $name, $phone, $email, $addressId)
    {
        $this->setBannerId($bannerId);
        $this->setUserId($userId);
        $this->setName($name);
        $this->setPhone($phone);
        $this->setEmail($email);
        $this->setAddressId($addressId);
    }


    /**
     * Retourne un tableau contenant les informations du magasin.
     * @return array|mixed
     */
    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'user' => $this->getUser()->getArray(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'address' => $this->getAddress()->getArray()
        );
    }


    /**
     * Retourne l'identifiant de la bannière du magasin.
     * @return mixed
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * Retourne l'identifiant de l'utilisateur du magasin.
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }


    /**
     * Retourne le nom du magasin.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Retourne le numéro de téléphone du magasin.
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Retourne l'adresse courriel du magasin.
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Retourne l'identifiant de l'adresse du magasin.
     * @return mixed
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * Définit le nom du magasin.
     * @param $name
     * @throws Exception
     */
    public function setName($name)
    {
        if (strlen($name) > 30) {
            throw new Exception('The length of the store name is too long.');
        }

        $this->name = $name;
    }

    /**
     * Définit le numéro de téléphone du magasin.
     * @param $phone
     * @throws Exception
     */
    public function setPhone($phone)
    {
        if (!preg_match(self::REGEX_PHONE, $phone)) {
            throw new Exception('The phone number must be standard. (i.e. 123-456-7890)');
        }

        // 450-772-2403 => 4507722403
        $phone = preg_replace('/[^\d]/', '', $phone);

        // 4507722403 => 14507722403
        $phone = (strlen($phone) == 10 ? '1' : '') . $phone;

        $this->phone = $phone;
    }


    /**
     * Définit l'adresse courriel du magasin.
     * @param $email
     * @throws Exception
     */
    public function setEmail($email)
    {
        if (!preg_match(self::REGEX_EMAIL, $email)) {
            throw new Exception('The email address must be standard. (i.e. infos@dutailier.com.');
        }

        $this->email = $email;
    }


    /**
     * Définit l'identifiant de l'adresse du magasin.
     * @param $addressId
     */
    private function setAddressId($addressId)
    {
        $this->addressId = intval($addressId);
    }


    /**
     * Définit l'identifiant de la bannière du magasin.
     * @param int $bannerId
     */
    private function setBannerId($bannerId)
    {
        $this->bannerId = intval($bannerId);
    }


    /**
     * Définit l'identifiant de l'utilisateur du magasin.
     * @param int $userId
     */
    private function setUserId($userId)
    {
        $this->userId = intval($userId);
    }


    /**
     * Retourne l'utilisteur du magasin.
     * @return User
     */
    public function getUser()
    {
        return Users::Find($this->getUserId());
    }


    /**
     * Retourne l'adresse du magasin.
     * @return Address
     */
    public function getAddress()
    {
        return Addresses::Find($this->getAddressId());
    }
}