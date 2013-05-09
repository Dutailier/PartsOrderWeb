<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');

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
            $item = new CartItem($_GET['typeId'], $_GET['categoryId'], $_GET['serialGlider']);

            $cart = new SessionCart();
            // Vérifie que la quantité avant d'avoir ajouté le type de pièce
            // est inférieure à la quantité après afin de confirmer que la pièce
            // à belle et bien été retirée du panier d'achats.
            if ($cart->getQuantity($item) > ($quantity = $cart->remove($item))) {
                $data['success'] = true;
                $data['quantity'] = $quantity;
            } else {
                $data['success'] = false;
                $data['message'] = 'Unable to remove the part form cart.';
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