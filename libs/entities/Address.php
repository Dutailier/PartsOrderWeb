<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/states.php');

/**
 * Class Address
 * Représente une adresse.
 */
class Address extends Entity
{
    const REGEX_ZIP = '/^\d{5}$/';
    private $details;
    private $city;
    private $zip;
    private $stateId;

    /**
     * Initialise l'adresse.
     * @param $details
     * @param $city
     * @param $zip
     * @param $stateId
     */
    function __construct($details, $city, $zip, $stateId)
    {
        $this->setDetails($details);
        $this->setCity($city);
        $this->setZip($zip);
        $this->setStateId($stateId);
    }


    /**
     * Retourne un tableau contenant les informations de l'adresse.
     * @return array|mixed
     */
    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'details' => $this->getDetails(),
            'city' => $this->getCity(),
            'zip' => $this->getZip(),
            'state' => $this->getState()->getArray()
        );
    }


    /**
     * Retourne les détails de l'adresse. (ex : 299, rue Chaput)
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }


    /**
     * Retourne la ville de l'adresse.
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * Retourne le code postal de l'adresse.
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }


    /**
     * Retourne l'identitifiant de l'état de l'adresse.
     * @return mixed
     */
    function getStateId()
    {
        return $this->stateId;
    }


    /**
     * Définit les détails de l'adresse. (ex: 299, rue Chaput)
     * @param $details
     * @throws Exception
     */
    public function setDetails($details)
    {
        if (strlen($details) > 255) {
            throw new Exception('The length of the details is too long.');
        }

        $this->details = trim($details);
    }


    /**
     * Définit la ville de l'adresse.
     * @param $city
     * @throws Exception
     */
    public function setCity($city)
    {
        if (strlen($city) > 50) {
            throw new Exception('The length of the city is too long.');
        }

        $this->city = trim($city);
    }


    /**
     * Définit le code postal de l'adresse.
     * @param $zip
     * @throws Exception
     */
    public function setZip($zip)
    {
        if (!preg_match(Address::REGEX_ZIP, $zip)) {
            throw new Exception('The zip code must be 5 digits.');
        }

        $this->zip = $zip;
    }

    /**
     * Définit l'identifiant de l'état de l'adresse.
     * @param $stateId
     * @throws Exception
     */
    public function setStateId($stateId)
    {
        $this->stateId = intval($stateId);
    }

    /**
     * Retourne l'état de l'adresse.
     * @return State
     */
    public function getState()
    {
        return States::Find($this->getStateId());
    }
}