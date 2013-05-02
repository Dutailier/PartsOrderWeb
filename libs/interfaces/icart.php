<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart extends IteratorAggregate
{
    public function add(IComparable $object);

    public function remove(IComparable $object);

    public function getQuantity(IComparable $object);

    public function setQuantity(IComparable $object, $quantity);

    public function isEmpty();

    public function getItems();

    public function clear();
}