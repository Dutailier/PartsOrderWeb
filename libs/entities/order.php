<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');

class Order extends Entity
{
    private $shippingAddressId;
    private $storeId;
    private $receiverId;
    private $number;
    private $creationDate;
    private $lastModifiedDate;
    private $status;

    function __construct($shippingAddressId, $storeId, $receiverId, $number = null, $creationDate = null, $lastModifiedDate = null, $status = null)
    {
        $this->storeId = intval($storeId);
        $this->receiverId = intval($receiverId);
        $this->shippingAddressId = intval($shippingAddressId);
        $this->number = $number;
        $this->creationDate = $creationDate;
        $this->lastModifiedDate = $lastModifiedDate;
        $this->status = trim($status);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'retailerId' => $this->getStoreId(),
            'customerId' => $this->getReceiverId(),
            'shippingAddressId' => $this->getShippingAddressId(),
            'number' => $this->getNumber(),
            'creationDate' => $this->getCreationDate(),
            'lastModifiedDate' => $this->getLastModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function setLastModifiedDate($lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStore()
    {
        return Stores::Find($this->getStoreId());
    }

    public function getReceiver()
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