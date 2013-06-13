<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/states.php');

class Address extends Entity
{
    const REGEX_ZIP = '/^\d{5}$/';
    private $details;
    private $city;
    private $zip;
    private $stateId;

    function __construct($details, $city, $zip, $stateId)
    {
        $this->setDetails($details);
        $this->setCity($city);
        $this->setZip($zip);
        $this->setStateId($stateId);
    }

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

    function getStateId()
    {
        return $this->stateId;
    }

    public function setCity($city)
    {
        $this->city = trim($city);
    }

    public function setDetails($details)
    {
        $this->details = trim($details);
    }

    public function setStateId($stateId)
    {
        $this->stateId = intval($stateId);
    }

    public function setZip($zip)
    {
        if (!preg_match(Address::REGEX_ZIP, $zip)) {
            throw new Exception('The zip code must be 5 digits.');
        }

        $this->zip = trim($zip);
    }

    public function getState()
    {
        return States::Find($this->getStateId());
    }
}