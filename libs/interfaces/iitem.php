<?php

include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class IItem
 * Représente un item contenu dans un panier d'achats.
 */
interface IItem extends IComparable
{
    /**
     * Retourne la quantité.
     * @return mixed
     */
    public function getQuantity();

    /**
     * Définit la quantité.
     * @param $quantity
     * @return mixed
     */
    public function setQuantity($quantity);
}