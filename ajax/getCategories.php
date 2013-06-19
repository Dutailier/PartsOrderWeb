<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/categories.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $transaction = new SessionTransaction();
        $destinationId = $transaction->getDestination()->getId();
        $categories = Categories::FilterByDestinationId($destinationId);

        $data['categories'] = array();
        foreach ($categories as $category) {
            $data['categories'][] = $category->getArray();
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);