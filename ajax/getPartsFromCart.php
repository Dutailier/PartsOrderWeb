<?php

include_once('../config.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/models/item.php');
include_once(ROOT . 'libs/models/part.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    $items = (new SessionCart())->getItems();
    $count = count($items);

    if (count($items) <= 0) {
        // Retourne un tableau vide car sinon il n'y aura pas la propriété
        // parts de $data et cela sera interprété comme une erreur.
        $data['parts'] = array();
    } else {

        // Parcours tous les items du panier d'achats afin
        // de construite l'objet JSON.
        foreach ($items as $item) {
            $part = $item->getObject();

            $data['parts'][] = array(
                "type" => $part->getType(),
                "name" => $part->getName(),
                "serial" => $part->getSerial(),
                "quantity" => $item->getQuantity()
            );
        }
    }

    // Confirme le succès de la requête.
    $data['success'] = true;
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
