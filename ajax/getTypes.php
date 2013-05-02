<?php

include_once('../config.php');
include_once(ROOT . 'libs/models/type.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/models/part.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['category']) || empty($_GET['serial'])) {
        $data['success'] = false;
        $data['message'] = 'A cateogry must be selected or a serial number must be entered.';
    } else {

        try {
            $data['types'] = Type::getTypes($_GET['category']);

            $count = count($data['types']);

            $cart = new SessionCart();
            for ($i = 0; $i < $count; $i++) {
                $data['types'][$i]['quantity'] =
                    $cart->getQuantity(new Part(
                        $data['types'][$i]['id'],
                        $data['types'][$i]['name'],
                        $_GET['serial']));;
            }

            $data['success'] = true;

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