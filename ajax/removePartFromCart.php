<?php

include_once('../config.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/models/part.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['type']) || empty($_GET['serial']) || empty($_GET['name'])) {
        $data['success'] = false;
        $data['message'] = 'A part must be selected or a serial number must be entered.';
    } else {

        // Crée une pièce à partir des informations passés en GET.
        $part = new Part($_GET['type'], $_GET['name'], $_GET['serial']);

        $cart = new SessionCart();
        // Vérifie que la quantité avant d'avoir ajouté le type de pièce
        // est inférieure à la quantité après afin de confirmer que la pièce
        // à belle et bien été retirée du panier d'achats.
        if ($cart->getQuantity($part) > ($qty = $cart->decrements($part))) {
            $data['success'] = true;
            $data['quantity'] = $qty;
        } else {
            $data['success'] = false;
            $data['message'] = 'Unable to remove the part form cart.';
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);