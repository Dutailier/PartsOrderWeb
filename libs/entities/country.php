<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/states.php');

class Country extends Entity
{
    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName()
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStates()
    {
        return States::FilterByCountryId($this->getId());
    }
}