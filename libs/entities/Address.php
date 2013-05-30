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
        if (!preg_match(Address::REGEX_ZIP, $zip)) {
            throw new Exception('The zip code must be 5 digits.');
        }

        $this->details = trim($details);
        $this->city = trim($city);
        $this->zip = trim($zip);
        $this->stateId = intval($stateId);
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

    function getState()
    {
        return States::Find($this->getStateId());
    }
}