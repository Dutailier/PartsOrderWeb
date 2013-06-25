<?php

include_once(ROOT . 'libs/interfaces/iitem.php');

/**
 * Class ICart
 * Définit les méthodes nécessaires à un panier d'achats.
 */
interface ICart
{
    /**
     * Copie le panier d'achats.
     * @param ICart $cart
     * @return mixed
     */
    public function Copy(ICart $cart);

    /**
     * Ajoute un item au panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function Add(IItem $item);

    /**
     * Retire un item du panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function Remove(IItem $item);

    /**
     * Retourne la quantité d'un item contenu dans le panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function getQuantity(IItem $item);

    /**
     * Définit la quantité d'un item contenu dans le panier d'achats.
     * @param IItem $item
     * @param $quantity
     * @return mixed
     */
    public function setQuantity(IItem $item, $quantity);

    /**
     * Retourne vrai si le panier d'achats est vide.
     * @return mixed
     */
    public function isEmpty();

    /**
     * Retourne les items contenus dans le panier d'achats.
     * @return mixed
     */
    public function getItems();

    /**
     * Vide le panier d'achats.
     * @return mixed
     */
    public function Clear();
}