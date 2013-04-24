<?php

require_once 'cart.php';

if (empty($_GET['partType_id']) || empty($_GET['serial_glider'])) {
    $data['success'] = false;
    $data['message'] = 'A partType must be selected or a serial number must be entered.';
} else {

    // Récupère les informations passées en GET.
    $serial_glider = $_GET['serial_glider'];
    $partType_id = $_GET['partType_id'];

    // Vérifie que la quantité avant d'avoir ajouté le type de pièce
    // est inférieure à la quantité après.
    if (Cart::getQuantity($serial_glider, $partType_id) <
        ($qty = Cart::Add($serial_glider, $partType_id))
    ) {
        $data['success'] = true;
        $data['partType_quantity'] = $qty;
    } else {
        $data['success'] = false;
        $data['message'] = 'Unable to add the item to the shopping cart.';
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
