<?php

/**
 * Class Item
 * Représente un item qu'on pourra commandé dans le panier d'achats.
 */
interface IComparable
{
    /**
     * Retourne vrai si l'item comparé est identique à celui-ci.
     * @param $obj
     * @return mixed
     */
    public function CompareTo($obj);
}