<?php

abstract class Entity
{
    private $id;

    public abstract function getArray();

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

}