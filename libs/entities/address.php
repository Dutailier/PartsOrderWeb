<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/states.php');

/**
 * Class Address
 * Représente une adresse.
 */
class Address
{
    // Propriétés
    private $id;
    private $details;
    private $city;
    private $zip;

    // Propriétés de navigation
    private $stateId;

    /**
     * Crée une adresse.
     * @param $id
     * @param null $details
     * @param null $city
     * @param null $zip
     * @param null $stateId
     */
    public function __construct($id, $details, $city, $zip, $stateId)
    {
        $this->id = $id;
        $this->details = $details;
        $this->city = $city;
        $this->zip = $zip;
        $this->stateId = $stateId;
    }

    public function getState()
    {
        return States::Find($this->stateId);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'details' => $this->getDetails(),
            'city' => $this->getCity(),
            'zip' => (string)$this->getZip(),
            'stateId' => $this->getStateId()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function getStateId()
    {
        return $this->stateId;
    }
}