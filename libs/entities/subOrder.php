<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/security.php');

class SubOrder extends Entity
{
    private $orderId;
    private $destinationId;
    private $number;
    private $status;

    function __construct(
        $orderId, $destinationId, $number = null, $creationDate = null)
    {
        $this->setOrderId($orderId);
        $this->setDestinationId($destinationId);
        $this->setNumber($number);
        $this->setCreationDate($creationDate);
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'orderId' => $this->getOrderId(),
            'destinationId' => $this->getDestinationId(),
            'number' => $this->getNumber(),
            'status' => $this->getStatus()
        );
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = intval($orderId);
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $destinationId
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = intval($destinationId);
    }

    /**
     * @return mixed
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = trim($number);
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = trim($status);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


}