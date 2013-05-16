<?php

include_once('config.php');
include_once(ROOT . 'libs/entities/part.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $part;
    private $categoryId;
    private $serial;
    private $quantity;

    /**
     * Constructeur par défaut.
     * (Je préfère passer l'objet Part plutôt que son Id
     * pour ne pas faire de requêtes inutiles plus tard.)
     * @param Part $part
     * @param $categoryId
     * @param $serial
     * @param int $quantity
     */
    public function __construct(Part $part, $categoryId, $serial, $quantity = 1)
    {
        $this->part = $part;
        $this->categoryId = $categoryId;
        $this->serial = $serial;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'part' => $this->getPart()->getArray(),
            'categoryId' => $this->getCategoryId(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getPart()
    {
        return $this->part;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function GetCategory()
    {
        include_once(ROOT . 'libs/repositories/categories.php');

        return Categories::Find($this->getCategoryId());
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
            $object->categoryId == $this->categoryId &&
            $object->serial == $this->serial;
    }
}