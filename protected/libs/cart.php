<?php

include_once(dirname(__FILE__) . '/item.php');

class Cart
{
    /**
     * Incrémente la quantité de cet item dans le panier d'achats et
     * retourne sa quantité suivant l'opération.
     * @param Item $item
     * @return mixed
     */
    public static function Add(Item $item)
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        // S'ils ne sont pas déjà créés, nous créons 2 tableaux qui travaillerons
        // en parallèle pour garder les informations du panier d'achats.
        if (!isset($_SESSION['items'])) {
            $_SESSION['items'] = array();
            $_SESSION['quantities'] = array();
        }

        $i = Cart::getIndex($item);

        // Si l'index est inférieur au nombre d'items dans le panier d'achats, c'est que
        // l'item y est déjà contenu, alos nous incrémentons sa quantité. Autrement,
        // nous l'ajoutons au panier d'achats.
        if ($i < count($_SESSION['items'])) {
            $_SESSION['quantities'][$i]++;
        } else {
            $_SESSION['items'][$i] = $item;
            $_SESSION['quantities'][$i] = 1;
        }

        return $_SESSION['quantities'][$i];
    }


    /**
     * Décrémente la quantité contenu dans le panier d'achats de l'item.
     * @param Item $item
     * @return int
     */
    public static function Remove(Item $item)
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        // Si le panier d'achat n'est pas instancié, il est vide.
        if (!isset($_SESSION['items'])) {
            return 0;
        }

        $i = Cart::getIndex($item);

        // Si l'index est inférieur au nombre d'items dans le panier d'achats, c'est que
        // l'item y est déjà contenu, alos nous décrémentons sa quantité. Autrement,
        // on retourne 0.
        if ($i < count($_SESSION['items'])) {

            // Si la quantité est déjà nulle, inutile de décrémenter.
            if ($_SESSION['quantities'][$i] > 0) {
                $_SESSION['quantities'][$i]--;
            }

            // Retourne la quantité restante.
            return $_SESSION['quantities'][$i];
        } else {
            return 0;
        }
    }


    /**
     * Retourne la quantité contenu par le panier d'achats de l'item.
     * @param Item $item
     * @return int
     */
    public static function getQuantity(Item $item)
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        // Si le panier d'achat n'est pas instancié, il est vide.
        if (!isset($_SESSION['items'])) {
            return 0;
        }

        $i = Cart::getIndex($item);

        // Si l'index est inférieur au nombre d'items dans le panier d'achats, c'est que
        // l'item y est déjà contenu, alos nous retournons sa quantité. Autrement,
        // on retourne 0.
        if ($i < count($_SESSION['items'])) {
            return $_SESSION['quantities'][$i];
        } else {
            return 0;
        }
    }

    /**
     * Définit la quantité d'un item contenu dans le panier d'achats.
     * Retourne faux si l'item ne figure pas dans le panier d'achats.
     * @param Item $item
     * @param $qty
     * @return bool
     */
    public static function setQuantity(Item $item, $qty)
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        // Vérifie que les tableaux ont bien été instancié,
        // sinon on retourne faux pour signaler l'erreur.
        if (!isset($_SESSION['items'])) {
            return false;
        }

        $i = Cart::getIndex($item);

        // Si l'index est inférieur au nombre d'items dans le panier d'achats, c'est que
        // l'item y est déjà contenu, alos nous retournons sa quantité. Autrement,
        // on retourne faux pour signaler l'erreur.
        if ($i < count($_SESSION['items'])) {
            $_SESSION['quantities'][$i] = $qty;

            return true;
        } else {
            return false;
        }
    }

    public static function getAll()
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        // Vérifie que les tableaux ont bien été instancié,
        // sinon on retourne un tableau vide.
        if (!isset($_SESSION['items'])) {
            return array();
        }

        $max = count($_SESSION['items']);
        $cart = array();

        // Parcours tous les items du panier d'achats afin de retourner les items
        // qui n'ont pas une quantité nulle.
        for ($i = 0, $j = 0; $i < $max; $i++) {
            if (!empty($_SESSION['quantities'][$i]) > 0) {
                $cart[$j]['item'] = $_SESSION['items'][$i];
                $cart[$j]['quantity'] = $_SESSION['quantities'][$i];
                $j++;
            }
        }

        return $cart;
    }

    /**
     * Efface le contenu d'un panier d'achats.
     */
    public static function Clear()
    {
        // Démarre une session si celle-ci n'est pas déjà active.
        if (!isset($_SESSION)) {
            session_start();
        }

        unset($_SESSION['items']);
        unset($_SESSION['quantities']);
    }

    /**
     * Retourne l'index de l'item si celui-ci y figure ou retourne
     * le nombre d'items contenus dans le panier d'achats si l'item
     * n'y ait pas contenu.
     * @param Item $item
     * @return int
     */
    private static function getIndex(Item $item)
    {
        $max = count($_SESSION['items']);

        // Parcours tous les items du panier d'achats afin de trouver
        // l'index de l'item.
        for ($i = 0; $i < $max; $i++) {
            if ($_SESSION['items'][$i]->Compare($item)) {
                return $i;
            }
        }
        return $max;
    }
}