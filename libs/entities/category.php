<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Category extends Entity
{
    private $id;
    private $name;
    private $customerInfosAreRequired;

    function __construct($id, $name, $customerInfosAreRequired)
    {
        $this->id = $id;
        $this->name = $name;
        $this->customerInfosAreRequired = $customerInfosAreRequired;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'customerInfosAreRequired' => $this->CustomerInfosAreRequired()
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

    public function CustomerInfosAreRequired()
    {
        return $this->customerInfosAreRequired;
    }

    public function getParts()
    {
        include_once(ROOT . 'libs/repositories/parts.php');

        return Parts::FilterByCategoryId($this->getId());
    }
}