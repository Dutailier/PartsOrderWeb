<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/states.php');


/**
 * Class Country
 * Représente un pays.
 */
class Country
{
    // Propriétés
    private $id;
    private $name;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'details' => $this->getName(),
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

    public function getStates()
    {
        return States::FilterByCountryId($this->id);
    }
}