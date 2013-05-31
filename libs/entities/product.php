<?php

include_once(ROOT . 'libs/entity.php');


class Product extends Entity
{
    private $name;
    private $description;

    function __construct($name, $description)
    {
        $this->name = trim($name);
        $this->description = trim($description);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription()
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
}