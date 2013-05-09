<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');
include_once(ROOT . 'libs/repositories/types.php');
include_once(ROOT . 'libs/repositories/categories.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $typeId;
    private $categoryId;
    private $serialGlider;
    private $quantity;

    public function __construct($typeId, $categoryId, $serialGlider, $quantity = 1)
    {
        $this->typeId = $typeId;
        $this->categoryId = $categoryId;
        $this->serialGlider = $serialGlider;
        $this->quantity = $quantity;
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
            $object->typeId == $this->typeId &&
            $object->categoryId == $this->categoryId &&
            $object->serialGlider == $this->serialGlider;
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

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCategory()
    {
        return Categories::Find($this->categoryId);
    }

    public function getSerialGlider()
    {
        return $this->serialGlider;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getType()
    {
        return Types::Find($this->typeId);
    }
}