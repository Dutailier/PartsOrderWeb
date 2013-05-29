<?php

include_once(ROOT . 'libs/entity.php');

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
        include_once(ROOT . 'libs/repositories/stores.php');

        return Stores::Find($this->getStoreId());
    }

    public function getCustomer()
    {
        include_once(ROOT . 'libs/repositories/receivers.php');

        return Receivers::Find($this->getReceiverId());
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
}