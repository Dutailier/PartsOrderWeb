<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/parts.php');

/**
 * Class Order
 * Représente une commande de pièces.
 */
class Order
{
    private $id;
    private $retailerId;
    private $customerId;
    private $isConfirmed;

    public function __construct($id, $retailerId, $customerId = null, $isConfirmed)
    {
        $this->id = $id;
        $this->retailerId = $retailerId;
        $this->customerId = $customerId;
        $this->isConfirmed = $isConfirmed;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getCustomer()
    {
        if (is_null($this->customerId)) {
            throw new Exception('No customer available.');
        }

        return Customers::Find($this->customerId);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    public function getRetailerId()
    {
        return $this->retailerId;
    }

    public function getRetailer()
    {
        return Retailers::Find($this->retailerId);
    }

    public function getParts()
    {
        return Parts::FilterByOrderId($this->id);
    }
}