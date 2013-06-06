<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {

    if (empty($_POST['orderId'])) {
        $data['success'] = false;
        $data['message'] = 'A order must be selected.';
    } else {

        try {
            $user = Security::getUserConnected();
            $store = $user->getStore();

            $order = Orders::Find($_POST['orderId']);

            if ($order->getStoreId() != $store->getId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';

            } else {
                $receiver = $order->getReceiver();
                $shippingAddress = $order->getShippingAddress();
                $lines = $order->getLines();

                $data['order'] = $order->getArray();
                $data['order']['receiver'] = $receiver->getArray();
                $data['order']['shippingAddress'] = $shippingAddress->getArray();

                $data['order']['lines'] = array();
                foreach ($lines as $line) {
                    $data['order']['lines'][] = $line->getArray();
                }

                $data['success'] = true;
            }

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);