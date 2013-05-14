<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orderHeaders.php');
include_once(ROOT . 'libs/repositories/orderLines.php');
include_once(ROOT . 'libs/repositories/retailers.php');
include_once(ROOT . 'libs/repositories/customers.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    if (empty($_GET['orderHeaderId'])) {
        $data['success'] = false;
        $data['message'] = 'A order must me placed.';
    } else {
        try {
            $orderHeader = OrderHeaders::Find($_GET['orderHeaderId']);
            $retailerId = $orderHeader->getRetailerId();

            if (!$retailerId == Retailers::getConnected()->getId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';
            } else {

                $data['orderHeader'] = $orderHeader->getArray();

                foreach (OrderLines::FilterByOrderHeaderId($orderHeader->getId()) as $orderLine) {
                    $data['orderLines'][] = $orderLine->getArray();
                }

                $data['success'] = true;
            }

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);
