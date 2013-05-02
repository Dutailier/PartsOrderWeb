<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');;

/**
 * Class Item
 * Représente un item contenu dans le panier d'achats.
 */
class Item
{
    private $object;
    private $quantity;

    /**
     * Constrcteur par défaut.
     * @param IComparable $object
     * @param int $quantity
     */
    public function __construct(IComparable $object, $quantity = 1)
    {
        $this->object = $object;
        $this->quantity = $quantity;
    }

    /**
     * Retourne l'objet.
     * @return IComparable
     */
    public function getObject()
    {
        return $this->object;
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
}