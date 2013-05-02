<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');
include_once(ROOT . 'libs/models/item.php');
include_once(ROOT . 'libs/interfaces/icart.php');

/**
 * Class Cart
 * Gère les méthodes relatives au panier d'achats.
 */
class SessionCart implements ICart
{
    const IDENTIFIER = '_CART_';
    protected $items;

    /**
     * Constructeur par défaut.
     * @param null $container Peut être une table, un cookie, etc...
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
     * @param IComparable $obj
     * @return mixed
     */
    public function add(IComparable $obj)
    {
        $index = $this->getIndexOfItem($obj);

        if ($index == -1) {
            $item = new Item($obj);
            $this->items[] = $item;

        } else {
            $item = $this->container[$index];
            $item->setQuantity($index->getQuantity() + 1);
        }

        return $item->getQuantity();
    }


    /**
     * Retire un item du panier d'achats.
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IComparable $obj
     * @return int
     * @throws Exception
     */
    public function remove(IComparable $obj)
    {
        $index = $this->getIndexOfItem($obj);

        if ($index == -1) {
            throw new Exception('The item isn\'t inside the cart.');
        }

        unset($this->items[$index]);

        return 0;
    }

    /**
     * Retourne la quantité de l'item contenue dans le panier d'achats.
     * @param IComparable $obj
     * @return mixed
     * @throws Exception
     */
    public function getQuantity(IComparable $obj)
    {
        $index = $this->getIndexOfItem($obj);

        if ($index == -1) {
            throw new Exception('The item isn\'t inside the cart.');
        }

        return $this->items[$index]->getQuantity();
    }

    /**
     * Définit la quantité contenue dans le panier d'achats de l'item.
     * @param IComparable $obj
     * @param $quantity
     * @return mixed
     * @throws Exception
     */
    public function setQuantity(IComparable $obj, $quantity)
    {
        if (($quantity = (int)$quantity) < 0) {
            throw new Exception('A positive quantity is required.');
        }

        $index = $this->getIndexOfItem($obj);

        if ($index == -1) {
            throw new Exception('The item isn\'t inside the cart.');

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
    public function clear()
    {
        $this->items = array();
    }


    /**
     * Retourne l'index de l'item.
     * @param IComparable $obj
     * @return array
     */
    private function getIndexOfItem(IComparable $obj)
    {
        foreach ($this->items as $key => $item) {
            if ($obj->equals($item->getItem())) {
                return $key;
            }
        }
        return -1;
    }


    /**
     * Retourne un tableau itérable contenant les items du panier d'achats.
     * @return Traversable
     */
    public function getIterator()
    {
        return ArrayIterator($this->items);
    }
}
