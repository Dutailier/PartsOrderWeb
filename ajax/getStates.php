<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/states.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['countryId'])) {
        $data['success'] = false;
        $data['message'] = 'A country must be selected.';
    } else {

        try {
            $states = array();
            $country = Countries::Find($_GET['countryId']);
            foreach ($country->getStates() as $state) {
                $data['states'][] = array(
                    'id' => $state->getId(),
                    'name' => $state->getName()
                );
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