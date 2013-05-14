<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');
include_once(ROOT . 'libs/repositories/parts.php');
include_once(ROOT . 'libs/repositories/categories.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $partId;
    private $categoryId;
    private $serialGlider;
    private $quantity;

    public function __construct($partId, $categoryId, $serialGlider, $quantity = 1)
    {
        $this->partId = $partId;
        $this->categoryId = $categoryId;
        $this->serialGlider = $serialGlider;
        $this->quantity = $quantity;
    }

    public function getArray()
    {
        return array(
            'partId' => $this->getPartId(),
            'categoryId' => $this->getCategoryId(),
            'serialGlider' => $this->getSerialGlider(),
            'quantity' => $this->getQuantity()
        );
    }

    public function getPartId()
    {
        return $this->partId;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getSerialGlider()
    {
        return $this->serialGlider;
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
     * Permet de définir la quantité d'un item.
     * @param $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPart()
    {
        return Parts::Find($this->partId);
    }

    /**
     * Retourne vrai si l'objet est identique à celui-ci.
     * @param $object
     * @return bool
     */
    public function equals($object)
    {
        return
            $object instanceof self &&
            $object->partId == $this->partId &&
            $object->categoryId == $this->categoryId &&
            $object->serialGlider == $this->serialGlider;
    }

    public function getCategory()
    {
        return Categories::Find($this->categoryId);
    }
}