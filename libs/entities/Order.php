<?php

include_once('config.php');

class Order
{
    private $id;
    private $storeId;
    private $customerId;
    private $shipmentId;
    private $creationDate;
    private $deliveryDate;
    private $status;

    function __construct($id, $storeId, $customerId, $shipmentId, $creationDate, $deliveryDate, $status)
    {
        $this->id = $id;
        $this->storeId = $storeId;
        $this->customerId = $customerId;
        $this->shipmentId = $shipmentId;
        $this->creationDate = $creationDate;
        $this->deliveryDate = $deliveryDate;
        $this->status = $status;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'storeId' => $this->getStoreId(),
            'customerId' => $this->getCustomerId(),
            'shipmentId' => $this->getShipmentId(),
            'creationDate' => $this->getCreationDate(),
            'deliveryDate' => $this->getDeliveryDate(),
            'status' => $this->getStatus()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getShipmentId()
    {
        return $this->shipmentId;
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
}