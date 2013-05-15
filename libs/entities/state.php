<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class State extends Entity
{
    private $id;
    private $name;
    private $countryId;

    function __construct($id, $name, $countryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'countryId' => $this->getCountryId()
        );
    }

    public function getId()
    {
        return $this->id;
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
        include_once(ROOT . 'libs/repositories/countries.php');

        return Countries::Find($this->getCountryId());
    }
}