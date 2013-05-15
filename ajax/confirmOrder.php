<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/retailers.php');

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
                $order->Confirm();
                $data['success'] = true;
            }

        } catch
        (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
