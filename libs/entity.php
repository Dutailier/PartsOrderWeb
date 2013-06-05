<?php

abstract class Entity
{
    private $id;
    private $isAttached;

    public abstract function getArray();

    public function setId($id)
    {
        $this->id = $id;
        $this->isAttached = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isAttached()
    {
        return $this->isAttached;
    }

    public function Detach()
    {
        $this->id = null;
        $this->isAttached = false;
    }
}