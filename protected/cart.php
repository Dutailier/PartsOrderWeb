<?php

class Cart
{
    /**
     * Ajoute une type de pièce au panier d'achat.
     * @param $serial_glider
     * @param $partType_id
     * @param $Qty
     */
    public static function Add($serial_glider, $partType_id, $Qty = 1)
    {
        session_start();

        // S'ils ne sont pas déjà créés, nous créons 3 tableaux qui travaillerons
        // en parallèle pour garder les informations du panier d'achats.
        if (!isset($_SESSION['partTypes'])) {
            $_SESSION['serials'] = array();
            $_SESSION['partTypes'] = array();
            $_SESSION['counts'] = array();
        }

        $i = 0; // Variable d'itération.

        // Parcours tous les types de pièces afin de trouvé
        // si elle figure déjà dans le panier d'achats.
        while ($i < count($_SESSION['partTypes']) &&
            $_SESSION['partTypes'][$i] != $partType_id &&
            $_SESSION['serials'][$i] != $serial_glider) {
            $i++;
        }

        // Si le type de pièce figure déjà dans le panier d'achats,
        // nous incrémentons sa quantité. Autrement, nous l'ajoutons.
        if ($i < count($_SESSION['partTypes'])) {
            $_SESSION['counts'][$i] += $Qty;
        } else {
            $_SESSION['serials'][$i] = $serial_glider;
            $_SESSION['partTypes'][$i] = $partType_id;
            $_SESSION['counts'][$i] = $Qty;
        }

        return true;
    }


    /**
     * Retire un type de pièce du panier d'achat.
     * @param $serial_glider
     * @param $partType_id
     * @param int $Qty
     */
    public static function Remove($serial_glider, $partType_id, $Qty = 1)
    {
        session_start();

        // Si le panier d'achat n'est pas instancié, c'est parce qu'il
        // est vide.
        if (!isset($_SESSION['partTypes'])) {
            return;
        }

        $i = 0; // Variable d'itération.

        // Parcours tous les types de pièces afin de trouvé
        // si elle figure dans le panier d'achats.
        while ($i < count($_SESSION['partTypes']) &&
            $_SESSION['partTypes'][$i] != $partType_id &&
            $_SESSION['serials'][$i] != $serial_glider) {
            $i++;
        }

        // Si le type de pièce figure dans le panier d'achats,
        // nous décrémentons sa quantité. Autrement, le type
        // de pièce ne figure pas dans le panier d'achats.
        if ($i < count($_SESSION['partTypes'])) {
            if ($Qty >= $_SESSION['counts'][$i]) {
                $_SESSION['counts'][$i] = 0;
            } else {
                $_SESSION['counts'][$i] -= $Qty;
            }
        } else {
            return;
        }
    }


    /**
     * Permet de connaître la quantité commandée d'un type de pièce.
     * @param $serial_glider
     * @param $partType_id
     * @return bool
     */
    public static function getQuantity($serial_glider, $partType_id)
    {
        session_start();

        // Si le panier d'achat n'est pas instancié, c'est parce qu'il
        // est vide.
        if (!isset($_SESSION['partTypes'])) {
            return 0;
        }

        $i = 0; // Variable d'itération.

        // Parcours tous les types de pièces afin de trouvé
        // si elle figure dans le panier d'achats.
        while ($i < count($_SESSION['partTypes']) &&
            $_SESSION['partTypes'][$i] != $partType_id &&
            $_SESSION['serials'][$i] != $serial_glider) {
            $i++;
        }

        // Si le type de pièce figure dans le panier d'achats,
        // nous retournons sa quantité. Autrement, c'est que
        // le type de pièce ne figure pas dans le panier d'achat.
        if ($i < count($_SESSION['partTypes'])) {
            return $_SESSION['counts'][$i];
        } else {
            return 0;
        }
    }


    /*
     *  Efface le contenu du panier d'achats.
     */
    public static function Clear()
    {
        session_start();

        unset($_SESSION['serials']);
        unset($_SESSION['partTypes']);
        unset($_SESSION['counts']);
    }
}