<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/countries.php');

class State extends Entity
{
    private $name;
    private $countryId;

    function __construct($name, $countryId)
    {
        $this->name = trim($name);
        $this->countryId = intval($countryId);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry()->getArray()
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCountryId()
    {
        return $this->countryId;
    }

    public function getCountry()
    {
        return Countries::Find($this->getCountryId());
    }
}