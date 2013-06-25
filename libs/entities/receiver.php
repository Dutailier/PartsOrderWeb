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
        $this->setName($name);
        $this->setPhone($phone);
        $this->setEmail($email);
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

    /**
     * Retourne le nom du destinataire.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Retourne le numéro de téléphone du destinataire.
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Retourne l'adresse courriel du destinataire.
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Définit le nom du destinataire.
     * @param $name
     * @throws Exception
     */
    public function setName($name)
    {
        if (strlen($name) > 50) {
            throw new Exception('The length of the store name is too long.');
        }

        $this->name = $name;
    }

    /**
     * Définit le numéro de téléphone du destinataire.
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
     * Définit l'adresse courriel du destinataire.
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
}