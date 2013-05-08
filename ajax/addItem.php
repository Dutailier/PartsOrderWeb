<?php

include_once('../config.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/models/part.php');
include_once(ROOT . 'libs/models/type.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['typeId']) || empty($_GET['name']) || empty($_GET['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'A item must be selected.';
    } else if (empty($_GET['serialGlider'])) {
        $data['success'] = false;
        $data['message'] = 'The serialGlider is required.';
    } else {

        try {
            $cateogry = new Category($_GET['categoryId']);
            $type = new Type($_GET['typeId'], $_GET['name'], $cateogry);
            $item = new CartItem($type, $_GET['serialGlider']);

            $cart = new SessionCart();
            // Vérifie que la quantité avant d'avoir ajouté le type de pièce
            // est inférieure à la quantité après afin de confirmer que
            // la pièce a belle et bien été ajoutée au panier d'achats.
            if ($cart->getQuantity($item) < ($quantity = $cart->Add($item))) {
                $data['success'] = true;
                $data['quantity'] = $quantity;
            } else {
                $data['success'] = false;
                $data['message'] = 'Unable to add the part to the cart.';
            }

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
