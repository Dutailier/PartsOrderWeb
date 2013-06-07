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
    private $lastModificationDate;
    private $status;

    function __construct($shippingAddressId, $storeId, $receiverId, $number = null, $creationDate = null, $lastModificationDate = null, $status = null)
    {
        $this->storeId = intval($storeId);
        $this->receiverId = intval($receiverId);
        $this->shippingAddressId = intval($shippingAddressId);
        $this->number = $number;
        $this->creationDate = $creationDate;
        $this->lastModificationDate = $lastModificationDate;
        $this->status = trim($status);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'storeId' => $this->getStoreId(),
            'receiverId' => $this->getReceiverId(),
            'shippingAddressId' => $this->getShippingAddressId(),
            'number' => $this->getNumber(),
            'creationDate' => $this->getCreationDate(),
            'lastModificationDate' => $this->getLastModificationDate(),
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

    public function setLastModificationDate($lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;
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

    public function getLastModificationDate()
    {
        return $this->lastModificationDate;
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