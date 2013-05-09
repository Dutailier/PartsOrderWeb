<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/retailers.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    try {
        $retailer = Retailers::getConnected();
        $address = $retailer->getAddress();

        $data['id'] = $retailer->getId();
        $data['name'] = $retailer->getName();
        $data['phone'] = $retailer->getPhone();
        $data['email'] = $retailer->getEmail();
        $data['address']['id'] = $address->getId();
        $data['address']['details'] = $address->getDetails();
        $data['address']['city'] = $address->getCity();
        $data['address']['zip'] = $address->getZip();
        $data['address']['state'] = $address->getState()->getName();
        $data['address']['country'] = $address->getState()->getCountry()->getName();
        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
