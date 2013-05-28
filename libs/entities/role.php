<?php

include_once(ROOT . 'libs/entity.php');

class Role extends Entity
{
    private $name;

    function __construct($name)
    {
        $this->name = trim($name);
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
}