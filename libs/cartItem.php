<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $typeId;
    private $name;
    private $serialGlider;
    private $quantity;

    /**
     * Constructeur par défaut.
     * @param $typeId
     * @param $name
     * @param $serialGlider
     * @param int $quantity
     */
    public function __construct($typeId, $name, $serialGlider, $quantity = 1)
    {
        $this->typeId = $typeId;
        $this->name = $name;
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

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSerialGlider()
    {
        return $this->serialGlider;
    }
}