<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Order extends Entity
{
    private $id;
    private $retailerId;
    private $customerId;
    private $shippingAddressId;
    private $creationDate;
    private $deliveryDate;
    private $status;

    function __construct($id, $retailerId, $customerId, $shippingAddressId, $creationDate, $deliveryDate, $status)
    {
        $this->id = $id;
        $this->retailerId = $retailerId;
        $this->customerId = $customerId;
        $this->shippingAddressId = $shippingAddressId;
        $this->creationDate = $creationDate;
        $this->deliveryDate = $deliveryDate;
        $this->status = $status;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'retailerId' => $this->getRetailerId(),
            'customerId' => $this->getCustomerId(),
            'shippingAddressId' => $this->getShippingAddressId(),
            'creationDate' => $this->getCreationDate(),
            'deliveryDate' => (is_null($this->getDeliveryDate()) ? 'Unknown' : $this->getDeliveryDate()),
            'status' => $this->getStatus()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRetailerId()
    {
        return $this->retailerId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getRetailer()
    {
        include_once(ROOT . 'libs/repositories/retailers.php');

        return Retailers::Find($this->getRetailerId());
    }

    public function getCustomer()
    {
        include_once(ROOT . 'libs/repositories/customers.php');

        if (is_null($this->getCustomerId())) {
            return null;
        } else {
            return Customers::Find($this->getCustomerId());
        }
    }

    public function getShippingAddress()
    {
        include_once(ROOT . 'libs/repositories/addresses.php');

        return Addresses::Find($this->getShippingAddressId());
    }

    public function getLines()
    {
        include_once(ROOT . 'libs/repositories/lines.php');

        return Lines::FilterByOrderId($this->getId());
    }

    public function Confirm()
    {
        include_once(ROOT . 'libs/repositories/orders.php');

        return Orders::Confirm($this->getId());
    }

    public function Cancel()
    {
        include_once(ROOT . 'libs/repositories/orders.php');

        return Orders::Cancel($this->getId());
    }

    public function addLine($partId, $categoryId, $serial, $quantity)
    {
        include_once(ROOT . 'libs/repositories/lines.php');

        return Lines::Add(
            $this->getId(),
            $partId,
            $categoryId,
            $serial,
            $quantity);
    }
}