<?php

include_once(ROOT . 'libs/interfaces/icart.php');
include_once(ROOT . 'libs/interfaces/iitem.php');

/**
 * Class Cart
 * Représente un panier d'achats contenu en session.
 * Gère les items contenus dans le panier.
 */
final class SessionCart implements ICart
{
    const ITEMS_IDENTIFIER = '_ITEMS_';

    /**
     * Constructeur par défaut.
     */
    public function __construct()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION[self::ITEMS_IDENTIFIER])) {
            $_SESSION[self::ITEMS_IDENTIFIER] = array();
        }
    }


    /**
     * Copie le contenu d'un autre panier d'achats.
     * @param ICart $cart
     */
    public function Copy(ICart $cart)
    {
        $_SESSION[self::ITEMS_IDENTIFIER] = $cart->getItems();
    }


    /**
     * Ajoute un item au panier d'achats.
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function Add(IItem $item)
    {
        $index = $this->getIndexOfItem($item);

        if ($index == -1) {
            $_SESSION[self::ITEMS_IDENTIFIER][] = $item;

        } else {
            $item = $_SESSION[self::ITEMS_IDENTIFIER][$index];
            $item->setQuantity($item->getQuantity() + 1);
        }

        return $item->getQuantity();
    }


    /**
     * Retire un item du panier d'achats.
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IItem $item
     * @return mixed
     * @throws Exception
     */
    public function Remove(IItem $item)
    {
        $index = $this->getIndexOfItem($item);

        if ($index == -1) {
            throw new Exception('The item isn\'t inside the cart.');
        }

        $item = $_SESSION[self::ITEMS_IDENTIFIER][$index];

        $quantity = $item->getQuantity() - 1;

        if ($quantity > 0) {
            $item->setQuantity($quantity);

        } else {
            unset($_SESSION[self::ITEMS_IDENTIFIER][$index]);
        }
        return $quantity;
    }


    /**
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IItem $item
     * @return int
     */
    public function getQuantity(IItem $item)
    {
        $index = $this->getIndexOfItem($item);

        if ($index == -1) {
            return 0;

        } else {
            return $_SESSION[self::ITEMS_IDENTIFIER][$index]->getQuantity();
        }
    }


    /**
     * Définit la quantité contenue dans le panier d'achats de l'item.
     * Peut être appelé pour ajouter un item d'une quantité supérieure à un.
     * @param IItem $item
     * @param $quantity
     * @return mixed
     * @throws Exception
     */
    public function setQuantity(IItem $item, $quantity)
    {
        if (($quantity = (int)$quantity) < 1) {
            throw new Exception('A positive quantity is required.');
        }

        $index = $this->getIndexOfItem($item);

        if ($index == -1) {
            $item->setQuantity($quantity);
            $_SESSION[self::ITEMS_IDENTIFIER][] = $item;

        } else {
            $item = $_SESSION[self::ITEMS_IDENTIFIER][$index];
            $item->setQuantity($quantity);
        }

        return $item->getQuantity();
    }


    /**
     * Retourne vrai si le panier d'achats ne contient aucun item.
     * @return bool
     */
    public function isEmpty()
    {
        return empty($_SESSION[self::ITEMS_IDENTIFIER]);
    }


    /**
     * Retourne un tableau fixe de tous les items.
     * dans le panier d'achats.
     * @return array
     */
    public function getItems()
    {
        return $_SESSION[self::ITEMS_IDENTIFIER];
    }


    /**
     * Vide le panier d'achats.
     */
    public function Clear()
    {
        $_SESSION[self::ITEMS_IDENTIFIER] = array();
    }


    /**
     * Retourne l'index de l'item.
     * @param IItem $item
     * @return int|string
     */
    private function getIndexOfItem(IItem $item)
    {
        foreach ($this->getItems() as $key => $value) {
            if ($item->Equals($value)) {
                return $key;
            }
        }
        return -1;
    }
}
