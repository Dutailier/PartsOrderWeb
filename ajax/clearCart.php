<?php

include_once('../config.php');
include_once(ROOT . 'libs/cart.php');

Cart::Clear();

// Confirme le succès de la requête.
$data['success'] = true;

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
