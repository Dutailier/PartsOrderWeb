<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    try {
        $user = Security::getUserConnected();
        $orders = array();

        if (Security::isInRoleName(ROLE_ADMINISTRATOR)) {
            $orders = Orders::All();

        } else if (Security::isInRoleName(ROLE_STORE)) {
            $store = $user->getStore();
            $orders = $store->getOrders();
        }

        $data['orders'] = array();
        foreach ($orders as $order) {
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