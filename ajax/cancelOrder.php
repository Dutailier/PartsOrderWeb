<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    if (empty($_GET['orderId'])) {
        $data['success'] = false;
        $data['message'] = 'A order must me placed.';

    } else {
        try {
            $order = Orders::Find($_GET['orderId']);
            $retailer = $order->getRetailer();

            if ($order->getRetailerId() != $retailer->getId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';

            } else {
                $order->Cancel();
                $data['success'] = true;
            }

        } catch
        (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);
