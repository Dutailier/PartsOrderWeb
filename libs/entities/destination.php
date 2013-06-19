<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/types.php');

class Destination extends Entity
{
    private $name;

    function __construct($name)
    {
        $this->setName($name);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName()
        );
    }

    public function setName($name)
    {
        $this->name = trim($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function GetTypes()
    {
        return Types::FilterByDestinationId($this->getId());
    }
}