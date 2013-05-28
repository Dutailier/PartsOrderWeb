<?php

include_once(ROOT . 'libs/item.php');
include_once(ROOT . 'libs/interfaces/icart.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class Cart
 * Représente un panier d'achats dans lequel ont y retrouve des items
 * de toutes sortes. Il démarrera la session par lui-même si nécessaire.
 */
class SessionCart implements ICart
{
    const IDENTIFIER = '_CART_';
    protected $items;

    /**
     * Constructeur par défaut.
     * @param null $container
     */
    public function __construct(&$container = null)
    {
        if (is_null($container)) {
            if (session_id() == '') {
                session_start();
            }
            if (!isset($_SESSION[self::IDENTIFIER])) {
                $_SESSION[self::IDENTIFIER] = array();
            }
            $container = & $_SESSION[self::IDENTIFIER];
        }
        $this->items = & $container;
    }


    /**
     * Ajoute un item au panier d'achats.
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IItem $item
     * @return mixed
     */
    public function add(IItem $item)
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
    public function remove(IItem $item)
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
     * Retourne l'index de l'item.
     * @param IItem $item
     * @return int|string
     */
    private function getIndexOfItem(IItem $item)
    {
        foreach ($this->items as $key => $value) {
            if ($item->Equals($value)) {
                return $key;
            }
        }
        return -1;
    }
}
