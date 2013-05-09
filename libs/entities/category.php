<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/types.php');

/**
 * Class Category
 * Représente une catégorie de pièces.
 */
class Category
{
    // Propriétés
    private $id;
    private $name;

    /**
     * Crée une catégorie.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getTypes()
    {
        return Types::FilterByCategoryId($this->id);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
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
}