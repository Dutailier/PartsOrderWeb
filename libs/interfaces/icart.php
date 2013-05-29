<?php

include_once(ROOT . 'libs/interfaces/iitem.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart
{
    public function Copy(ICart $cart);

    public function Add(IItem $item);

    public function Remove(IItem $item);

    public function getQuantity(IItem $item);

    public function setQuantity(IItem $item, $quantity);

    public function isEmpty();

    public function getItems();

    public function Clear();
}