<?php

include_once('config.php');

class Article
{
    private $id;
    private $orderId;
    private $name;
    private $description;
    private $quantity;
    private $unit;

    function __construct($id, $orderId, $name, $description, $quantity, $unit)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unit = $unit;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getUnit()
    {
        return $this->unit;
    }
}