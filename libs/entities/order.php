<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');

class Order extends Entity
{
    private $storeId;
    private $receiverId;
    private $shippingAddressId;
    private $creationDate;
    private $status;

    function __construct($shippingAddressId, $storeId, $receiverId, $creationDate = null, $status = null)
    {
        $this->storeId = intval($storeId);
        $this->receiverId = intval($receiverId);
        $this->shippingAddressId = intval($shippingAddressId);
        $this->creationDate = $creationDate;
        $this->status = $status;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'retailerId' => $this->getStoreId(),
            'customerId' => $this->getReceiverId(),
            'shippingAddressId' => $this->getShippingAddressId(),
            'creationDate' => $this->getCreationDate(),
            'status' => $this->getStatus()
        );
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
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
        return Stores::Find($this->getStoreId());
    }

    public function getCustomer()
    {
        return Receivers::Find($this->getReceiverId());
    }

    public function getShippingAddress()
    {
        return Addresses::Find($this->getShippingAddressId());
    }

    public function getLines()
    {
        return Lines::FilterByOrderId($this->getId());
    }

    public function Confirm()
    {
        return Orders::Confirm($this->getId());
    }

    public function Cancel()
    {
        return Orders::Cancel($this->getId());
    }
}