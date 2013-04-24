<?php

class Cart
{
    /**
     * Ajoute une type de pièce au panier d'achat.
     * @param $serial_glider
     * @param $partType_id
     * @param $Qty
     */
    public static function Add($serial_glider, $partType_id)
    {
		// Démarre une session si celle-ci n'est pas déjà active.
		if (!$_SESSION) {
			session_start();
		}

        // S'ils ne sont pas déjà créés, nous créons 3 tableaux qui travaillerons
        // en parallèle pour garder les informations du panier d'achats.
        if (!isset($_SESSION['partTypes'])) {
            $_SESSION['serials'] = array();
            $_SESSION['partTypes'] = array();
            $_SESSION['quantities'] = array();
        }

        $i = Cart::getIndex($serial_glider, $partType_id);

        // Si le type de pièce figure déjà dans le panier d'achats,
        // nous incrémentons sa quantité. Autrement, nous l'ajoutons.
        if ($i < count($_SESSION['partTypes'])) {
            $_SESSION['quantities'][$i]++;
        } else {
            $_SESSION['serials'][$i] = $serial_glider;
            $_SESSION['partTypes'][$i] = $partType_id;
            $_SESSION['quantities'][$i] = 1;
        }

        return $_SESSION['quantities'][$i];
    }


    /**
     * Retire un type de pièce du panier d'achat. Si aucune quantité
     * n'est inscrite, on retire la quantité actuelle.
     * @param $serial_glider
     * @param $partType_id
     * @param null $Qty
     * @return bool
     */
    public static function Remove($serial_glider, $partType_id)
    {
		// Démarre une session si celle-ci n'est pas déjà active.
		if (!$_SESSION) {
			session_start();
		}

        // Si le panier d'achat n'est pas instancié, c'est parce qu'il
        // est vide.
        if (!isset($_SESSION['partTypes'])) {
            return 0;
        }

        $i = Cart::getIndex($serial_glider, $partType_id);

        // Si le type de pièce figure dans le panier d'achats,
        // nous décrémentons sa quantité. Autrement, le type
        // de pièce ne figure pas dans le panier d'achats.
        if ($i < count($_SESSION['partTypes'])) {
            $_SESSION['quantities'][$i]--;

            // Retourne la quantité restante.
            return $_SESSION['quantities'][$i];
        } else {
            return 0;
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
		// Démarre une session si celle-ci n'est pas déjà active.
		if (!$_SESSION) {
			session_start();
		}

        // Si le panier d'achat n'est pas instancié, c'est parce qu'il
        // est vide.
        if (!isset($_SESSION['partTypes'])) {
            return 0;
        }

        $i = Cart::getIndex($serial_glider, $partType_id);

        // Si le type de pièce figure dans le panier d'achats,
        // nous retournons sa quantité. Autrement, c'est que
        // le type de pièce ne figure pas dans le panier d'achat.
        if ($i < count($_SESSION['partTypes'])) {
            return $_SESSION['quantities'][$i];
        } else {
            return 0;
        }
    }

    /**
     * Permet de retrouver l'index d'un type de pièce commandé.
     * @param $serial_glider
     * @param $partType_id
     */
    private static function getIndex($serial_glider, $partType_id)
    {
        $max = count($_SESSION['partTypes']);

        // Parcours tous les types de pièces afin de trouvé
        // s'il figure dans le panier d'achats.
        for ($i = 0; $i < $max; $i++) {
            if ($_SESSION['serials'][$i] == $serial_glider &&
                $_SESSION['partTypes'][$i] == $partType_id
            ) {
                return $i;
            }
        }

        return $max;
    }


    /**
     * Efface le contenu d'un panier d'achats.
     */
    public static function Clear()
    {
		// Démarre une session si celle-ci n'est pas déjà active.
		if (!$_SESSION) {
			session_start();
		}

        unset($_SESSION['serials']);
        unset($_SESSION['partTypes']);
        unset($_SESSION['quantities']);
    }
}