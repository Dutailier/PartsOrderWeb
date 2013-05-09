<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/types.php');

/**
 * Class Part
 * Représente une pièce.
 */
class Part
{
    private $id;
    private $sku;
    private $serialGlider;
    private $typeId;

    // Seulement disponible lorsque join à une commande.
    private $orderId;
    private $quantity;

    public function __construct(
        $id, $serialGlider, $typeId, $sku = null,
        // Seulement disponible lorsue join à une commande.
        $orderId = null, $quantity = null)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->serialGlider = $serialGlider;
        $this->typeId = $typeId;
        $this->orderId = $orderId;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'serialGlider' => $this->getSerialGlider(),
            'sku' => $this->getSku(),
            'typeId' => $this->getTypeId(),
            'orderId' => $this->getOrderId(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSerialGlider()
    {
        return $this->serialGlider;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getOrder()
    {
        if (!is_null($this->orderId)) {
            return Orders::Find($this->orderId);
        }
    }

    public function getType()
    {
        return Types::Find($this->typeId);
    }
}
