<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/products.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    try {
        $transaction = SessionTransaction::getCurrent();

        $filterIds = array();

        if (!empty($_POST['filterIds'])) {
            $filterIds = $_POST['filterIds'];
        }
        $filterIds[] = $transaction->getDestinationFilter()->getId();

        $data['products'] = array();
        foreach (Products::FilterByFilterIds($filterIds) as $product) {
            $data['products'][] = $product->Encode();
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);