<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/products.php');

class Line extends Entity
{
    const REGEX_SERIAL = '/^[0-9]{11}$/';
    private $orderId;
    private $productId;
    private $serial;
    private $quantity;
    private $sku;

    function __construct($orderId, $productId, $quantity, $serial, $sku = null)
    {
        if (!preg_match(Line::REGEX_SERIAL, $serial)) {
            throw new Exception('The serial must be 11 digits.');
        }

        $this->orderId = intval($orderId);
        $this->productId = intval($productId);
        $this->serial = trim($serial);
        $this->quantity = trim($quantity);
        $this->sku = trim($sku);
    }

    public function getArray()
    {
        return array(
            'orderId' => $this->getOrderId(),
            'product' => $this->getProduct()->getArray(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity(),
            'sku' => $this->getSku()
        );
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getProduct()
    {
        return Products::Find($this->getProductId());
    }

    public function getOrder()
    {
        return Orders::Find($this->getOrderId());
    }
}