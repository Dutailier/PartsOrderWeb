<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/iitem.php');
include_once(ROOT . 'libs/entities/product.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class Item implements IItem
{
    private $product;
    private $serial;
    private $quantity;

    /**
     * @param Product $product
     * @param $serial
     * @param int $quantity
     */
    public function __construct(Product $product, $serial, $quantity = 1)
    {
        $this->product = $product;
        $this->serial = $serial;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'product' => $this->getProduct()->getArray(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function Equals($object)
    {
        return
            $object instanceof self &&
            $object->product->getId() == $this->product->getId() &&
            $object->serial == $this->serial;
    }
}