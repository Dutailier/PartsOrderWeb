<?php

include_once('../config.php');
include_once(ROOT . 'libs/category.php');

try {
    $data['categories'] = Category::getCategories();
    $data['success'] = true;

} catch (Exception $e) {

    // Si la requête échoué, retourne un message d'erreur.
    $data['success'] = false;
    $data['message'] = $e->getMessage();
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);

