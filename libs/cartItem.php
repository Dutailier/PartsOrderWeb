<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class CartItem implements ICartItem
{
    private $type;
    private $serialGlider;
    private $quantity;

    /**
     * Constructeur par défaut.
     * @param $type
     * @param $serialGlider
     * @param int $quantity
     */
    public function __construct(Type $type, $serialGlider, $quantity = 1)
    {
        $this->type = $type;
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
            $object->type->getId() == $this->type->getId() &&
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

    /**
     * Retourne l'instance du type de cet item.
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retourne le numéro de série de la chaise associée à l'item.
     * @return mixed
     */
    public function getSerialGlider()
    {
        return $this->serialGlider;
    }
}