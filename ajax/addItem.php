<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/parts.php');
include_once(ROOT . 'libs/repositories/categories.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['partId']) || empty($_GET['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'A item must be selected.';
    } else if (empty($_GET['serial'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';
    } else {

        try {
            $part = Parts::Find($_GET['partId']);
            $category = Categories::Find($_GET['categoryId']);
            $item = new CartItem($part, $category, $_GET['serial']);

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

header('Content-type: application/json');
echo json_encode($data);
