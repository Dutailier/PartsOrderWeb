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
        $transaction = new SessionTransaction();

        $filterIds = array();
        if (!empty($_POST['filterIds'])) {
            $filterIds = $_POST['filterIds'];
        }
        $filterIds[] = $transaction->getDefaultFilter()->getId();

        $data['products'] = array();
        foreach (Products::FilterByFilterIds($filterIds) as $product) {
            $data['products'][] = $product->getArray();
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);