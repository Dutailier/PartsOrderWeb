<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Role extends Entity
{
    private $id;
    private $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName()
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