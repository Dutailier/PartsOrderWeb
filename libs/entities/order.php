<?php

include_once(ROOT . 'libs/entity.php');

class Order extends Entity
{
    private $retailerId;
    private $customerId;
    private $shippingAddressId;
    private $creationDate;
    private $status;

    function __construct($shippingAddressId, $retailerId, $customerId = null, $creationDate = null, $status = null)
    {
        $this->retailerId = $retailerId;
        $this->customerId = $customerId;
        $this->shippingAddressId = $shippingAddressId;
        $this->creationDate = $creationDate;
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
            'status' => $this->getStatus()
        );
    }

    public function getRetailerId()
    {
        return $this->retailerId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
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

    public function AddLine(Line $line)
    {
        include_once(ROOT . 'libs/repositories/lines.php');

        return Lines::Attach($line);
    }
}