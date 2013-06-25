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

    /**
     * Retourne un tableau contenant les informations de l'item.
     * @return array
     */
    public function getArray()
    {
        return array(
            'product' => $this->getProduct()->getArray(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity()
        );
    }

    /**
     * Retourne le produit lié à l'item.
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Retourne le numéro de série lié à l'item.
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Retourne la quantité de l'item.
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Définit la quantité de l'item.
     * @param $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Retourne vrai si l'objet est égale à l'item.
     * @param $object
     * @return bool
     */
    public function Equals($object)
    {
        return
            $object instanceof self &&
            $object->product->getId() == $this->product->getId() &&
            $object->serial == $this->serial;
    }
}