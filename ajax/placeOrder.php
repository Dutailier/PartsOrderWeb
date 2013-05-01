<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    // Validation des informations passées en POST.
    if (empty($_POST['firstname'])) {
        $data['success'] = false;
        $data['message'] = 'The first name is required.';
    } else if (empty($_POST['lastname'])) {
        $data['success'] = false;
        $data['message'] = 'The last name is required.';
    } else if (empty($_POST['email'])) {
        $data['success'] = false;
        $data['message'] = 'The email name is required.';
    } else if (empty($_POST['address'])) {
        $data['success'] = false;
        $data['message'] = 'The address name is required.';
    } else if (empty($_POST['city'])) {
        $data['success'] = false;
        $data['message'] = 'The city name is required.';
    } else if (empty($_POST['zip'])) {
        $data['success'] = false;
        $data['message'] = 'The zip name is required.';
    } else if (empty($_POST['state'])) {
        $data['success'] = false;
        $data['message'] = 'The state name is required.';
    } else if (empty($_POST['country'])) {
        $data['success'] = false;
        $data['message'] = 'The country name is required.';
    } else {

    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);