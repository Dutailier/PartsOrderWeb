<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/logs.php');
include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/comments.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');

class Order extends Entity
{
    private $shippingAddressId;
    private $storeId;
    private $receiverId;
    private $number;
    private $creationDate;
    private $status;

    function __construct(
        $shippingAddressId, $storeId, $receiverId, $number = null, $creationDate = null, $status = null)
    {
        $this->storeId = intval($storeId);
        $this->receiverId = intval($receiverId);
        $this->shippingAddressId = intval($shippingAddressId);
        $this->number = trim($number);
        $this->creationDate = $creationDate;
        $this->status = trim($status);
    }

    public function getArray()
    {
        $lastLog = $this->getLogs()[0];

        return array(
            'id' => $this->getId(),
            'storeId' => $this->getStoreId(),
            'receiverId' => $this->getReceiverId(),
            'shippingAddressId' => $this->getShippingAddressId(),
            'number' => $this->getNumber(),
            'creationDate' => $this->getCreationDate(),
            'lastModificationByUser' => $lastLog->getUser()->getArray(),
            'lastModificationDate' => $lastLog->getDatetime(),
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
        $status = $this->getStatus();

        if ($status != 'Pending') {
            throw new Exception('You can\'t confirm this order.');
        }

        return Orders::ConfirmByUserId($this->getId(), Security::getUserConnected()->getId());
    }

    public function Cancel()
    {
        $status = $this->getStatus();

        if ($status != 'Pending' && $status != 'Confirmed') {
            throw new Exception('You can\'t cancel this order.');
        }

        return Orders::CancelByUserId($this->getId(), Security::getUserConnected()->getId());
    }

    public function getComments()
    {
        return Comments::FilterByOrderId($this->getId());
    }

    public function getLogs()
    {
        return Logs::FilterByOrderId($this->getId());
    }
}