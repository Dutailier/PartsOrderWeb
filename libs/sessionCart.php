<?php

include_once(ROOT . 'libs/interfaces/icart.php');
include_once(ROOT . 'libs/interfaces/iitem.php');

/**
 * Class Cart
 * Représente un panier d'achats contenu en session.
 * Gère les items contenus dans le panier d'achats.
 */
final class SessionCart implements ICart
{
    const ITEMS_IDENTIFIER = '_ITEMS_';

    private $items;

    /**
     * Initialise le panier d'achats.
     */
    public function __construct()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION[self::ITEMS_IDENTIFIER])) {
            $_SESSION[self::ITEMS_IDENTIFIER] = array();
        }

        $this->items = & $_SESSION[self::ITEMS_IDENTIFIER];
    }


    /**
     * Copie le contenu d'un autre panier d'achats.
     * @param ICart $cart
     */
    public function Copy(ICart $cart)
    {
        $this->items = $cart->getItems();
    }


    /**
     * Ajoute un item au panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function Add(IItem $item)
    {
        $index = $this->getIndexOfItem($item);

        if ($index == -1) {
            $this->items[] = $item;

        } else {
            $item = $this->items[$index];
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

        $item = $this->items[$index];
        $quantity = $item->getQuantity() - 1;

        if ($quantity > 0) {
            $item->setQuantity($quantity);

        } else {
            unset($this->items[$index]);
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
            return $this->items[$index]->getQuantity();
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
            $this->items[] = $item;

        } else {
            $item = $this->items[$index];
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
        return empty($this->items);
    }


    /**
     * Retourne un tableau fixe de tous les items.
     * dans le panier d'achats.
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * Vide le panier d'achats.
     */
    public function Clear()
    {
        $this->items = array();
    }


    /**
     * Retourne l'index de l'item trouvé sinon retourne -1.
     * @param IItem $item
     * @return int
     */
    private function getIndexOfItem(IItem $item)
    {
        foreach ($this->items as $index => $value) {
            if ($item->Equals($value)) {
                return (int)$index;
            }
        }
        return -1;
    }
}
