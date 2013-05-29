<?php

include_once('../config.php');

include_once(ROOT . 'libs/security.php');
if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $store = Security::getStoreConnected();
        $address = $store->getAddress();

        $data['address'] = $address->getArray();
        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);