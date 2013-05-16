<?php

include_once('../config.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $cart = new SessionCart();

        foreach ($cart->getItems() as $item) {

            // Si le produit est commandé pour un client,
            // les informations du client sont nécessaires.
            if ($item->getCategory()->CustomerInfosAreRequired()) {
                $data['customerInfosAreRequired'] = true;
                break;
            }
        }

        // Si la propriété n'est pas définit, c'est qu'auncun
        // produit n'est commandé pour un client.
        if (!isset($data['customerInfosAreRequired'])) {
            $data['customerInfosAreRequired'] = false;
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);