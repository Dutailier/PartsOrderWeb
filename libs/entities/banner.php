<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/stores.php');

class Banner extends Entity
{
    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    public function getArray()
    {
        return array(
            'name' => $this->getName()
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStores()
    {
        return Stores::FilterByBannerId($this->getId());
    }
}