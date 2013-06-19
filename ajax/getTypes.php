<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/types.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $transaction = new SessionTransaction();
        $destinationId = $transaction->getDestination()->getId();
        $types = Types::FilterByDestinationId($destinationId);

        $data['types'] = array();
        foreach ($types as $type) {
            $data['types'][] = $type->getArray();
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);