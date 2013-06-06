<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $user = Security::getUserConnected();
        $store = $user->getStore();

        $data['orders'] = array();
        foreach ($store->getOrders() as $order) {
            $data['orders'][] = $order->getArray();
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);