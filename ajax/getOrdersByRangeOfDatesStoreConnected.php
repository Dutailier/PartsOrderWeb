<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['from']) || empty($_POST['to'])) {
        $data['success'] = true;
        $data['message'] = 'A rage of dates is required.';

    } else {
        try {
            $user = Security::getUserConnected();
            $stores = Stores::FilterByUserId($user->getId());

            $orders = Orders::FilterByRangeOfDatesAndStoreId(
                strtotime($_POST['from']),
                strtotime($_POST['to']),
                $stores[0]->getId());

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