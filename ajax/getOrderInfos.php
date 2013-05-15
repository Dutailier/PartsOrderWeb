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
            $retailer = Security::getRetailerConnected();
            $order = Orders::Find($_GET['orderId']);

            if ($order->getRetailerId() != $retailer->getId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';
            } else {

                $lines = $order->getLines();

                // Si la commande n'est reliée à aucun client,
                // le détaillant sera le client.
                $customer = is_null($order->getCustomerId()) ?
                    $retailer : $order->getCustomer();

                $data['retailer'] = $retailer->getArray();

                $address = $retailer->getAddress();
                $data['retailer']['address'] = $address->getArray();
                $data['retailer']['address']['state'] = $address->getState()->getArray();

                $data['customer'] = $customer->getArray();

                $address = $customer->getAddress();
                $data['customer']['address'] = $address->getArray();
                $data['customer']['address']['state'] = $address->getState()->getArray();

                foreach ($lines as $line) {
                    $entry = $line->getArray();
                    $entry['part'] = $line->getPart()->getArray();
                    $data['lines'][] = $entry;
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
