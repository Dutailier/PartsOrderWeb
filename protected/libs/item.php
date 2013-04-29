<?php

/**
 * Class Item
 * Représente un item qu'on pourra commandé dans le panier d'achats.
 */
abstract class Item {

    /**
     * Retourne vrai si l'item comparé est identique à celui-ci.
     * @param $item
     * @return mixed
     */
    public abstract function Compare(Item $item);
}