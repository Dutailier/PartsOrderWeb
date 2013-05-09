<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/countries.php');

/**
 * Class State
 * Représente un état/province.
 */
class State
{
    private $id;
    private $name;
    private $countryId;

    public function __construct($id, $name, $countryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
    }

    public function getCountryId()
    {
        return $this->countryId;
    }

    public function getCountry()
    {
        return Countries::Find($this->countryId);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}