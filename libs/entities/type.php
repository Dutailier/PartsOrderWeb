<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/categories.php');

/**
 * Class Type
 * Représente un type de pièce.
 */
class Type
{
    private $id;
    private $name;
    private $description;

    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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