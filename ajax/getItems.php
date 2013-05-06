<?php

include_once('../config.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/cartItem.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    try {
        $cart = new SessionCart();

        $data['items'] = array();
        foreach ($cart->getItems() as $part) {
            $data['items'][] = array(
                'typeId' => $part->getTypeId(),
                'name' => $part->getName(),
                'serialGlider' => $part->getSerialGlider(),
                'quantity' => $part->getQuantity()
            );
        }
        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
