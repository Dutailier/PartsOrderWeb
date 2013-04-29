<?php

include_once(dirname(__FILE__) . '/../libs/cart.php');
include_once(dirname(__FILE__) . '/../libs/part.php');

$cart = Cart::getAll();
$max = count($cart);

if (count($cart) <= 0) {
    // Retourne un tableau vide car sinon il n'y aura pas la propriété
    // parts de $data et cela sera interprété comme une erreur.
    $data['parts'] = array();
} else {

// Parcours tous les items du panier d'achats afin
// de les retourner en tant que pièce.
    for ($i = 0; $i < $max; $i++) {
        $data['parts'][$i]['type'] = $cart[$i]['item']->getType();
        $data['parts'][$i]['name'] = $cart[$i]['item']->getName();
        $data['parts'][$i]['serial'] = $cart[$i]['item']->getSerial();
        $data['parts'][$i]['quantity'] = $cart[$i]['quantity'];
    }
}

// Confirme le succès de la requête.
$data['success'] = true;

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
