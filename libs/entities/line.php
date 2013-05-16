<?php

include_once('config.php');
include_once(ROOT . 'libs/entity.php');

class Line extends Entity
{
    const REGEX_SERIAL = '/^[\d]{11}$/';
    private $orderId;
    private $partId;
    private $categoryId;
    private $serial;
    private $sku;
    private $quantity;

    function __construct($orderId, $partId, $categoryId, $serial, $sku, $quantity)
    {
        $this->orderId = $orderId;
        $this->partId = $partId;
        $this->categoryId = $categoryId;
        $this->serial = $serial;
        $this->sku = $sku;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'partId' => $this->getPartId(),
            'orderId' => $this->getOrderId(),
            'serial' => $this->getSerial(),
            'sku' => $this->getSku(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getPartId()
    {
        return $this->partId;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPart()
    {
        include_once(ROOT . 'libs/repositories/parts.php');

        return Parts::Find($this->getPartId());
    }

    public function getOrder()
    {
        include_once(ROOT . 'libs/repositories/orders.php');

        return Orders::Find($this->getOrderId());
    }

    public function getCategory()
    {
        include_once(ROOT . 'libs/repositories/categories.php');

        return Categories::Find($this->getCategoryId());
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }
}