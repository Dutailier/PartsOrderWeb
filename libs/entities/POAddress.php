<?php

include_once('config.php');

class POAddress
{
    private $id;
    private $details;
    private $city;
    private $zip;
    private $stateId;

    function __construct($id, $details, $city, $zip, $stateId)
    {
        $this->id = $id;
        $this->details = $details;
        $this->city = $city;
        $this->zip = $zip;
        $this->stateId = $stateId;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'details' => $this->getDetails(),
            'city' => $this->getCity(),
            'zip' => $this->getZip(),
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