<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::isInRoleName(ROLE_ADMINISTRATOR)) {
    $data['success'] = false;
    $data['message'] = 'You must be connected as administrator.';

} else {
    if (empty($_POST['number'])) {
        $data['success'] = true;
        $data['message'] = 'A number is required.';

    } else {
        try {
            $orders = Orders::FilterByNumber($_POST['number']);

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
}

header('Content-type: application/json');
echo json_encode($data);