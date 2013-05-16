<?php

include_once('config.php');
include_once(ROOT . 'libs/entities/part.php');
include_once(ROOT . 'libs/entities/category.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $part;
    private $category;
    private $serial;
    private $quantity;

    /**
     * @param Part $part
     * @param Category $category
     * @param $serial
     * @param int $quantity
     */
    public function __construct(Part $part, Category $category, $serial, $quantity = 1)
    {
        $this->part = $part;
        $this->category = $category;
        $this->serial = $serial;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'part' => $this->getPart()->getArray(),
            'category' => $this->getCategory()->getArray(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getPart()
    {
        return $this->part;
    }

    public function getCategory()
    {
        return $this->category;
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

    public function equals($object)
    {
        return
            $object instanceof self &&
            $object->part->getId() == $this->part->getId() &&
            $object->category->getId() == $this->category->getId() &&
            $object->serial == $this->serial;
    }
}