<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/products.php');

class Filter extends Entity
{
    private $name;
    private $type;

    function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
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

    public function getType()
    {
        return $this->type;
    }
}