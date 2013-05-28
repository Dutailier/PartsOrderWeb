<?php

include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart
{
    public function add(IItem $item);

    public function remove(IItem $item);

    public function getQuantity(IItem $item);

    public function setQuantity(IItem $item, $quantity);

    public function isEmpty();

    public function getItems();

    public function Clear();
}