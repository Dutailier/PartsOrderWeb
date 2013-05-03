<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart
{
    public function add(ICartItem $item);

    public function remove(ICartItem $item);

    public function getQuantity(ICartItem $item);

    public function setQuantity(ICartItem $item, $quantity);

    public function isEmpty();

    public function getItems();

    public function clear();
}