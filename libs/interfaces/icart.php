<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart extends IteratorAggregate
{
    public function add(IComparable $obj);

    public function remove(IComparable $obj);

    public function getQuantity(IComparable $obj);

    public function setQuantity(IComparable $obj, $quantity);

    public function isEmpty();

    public function getItems();

    public function clear();
}