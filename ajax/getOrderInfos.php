<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/retailers.php');
include_once(ROOT . 'libs/repositories/customers.php');

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

            if (!$retailer->equals(Retailers::getConnected())) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';
            } else {

                $parts = $order->getParts();

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

                $data['success'] = true;

                foreach ($parts as $part) {
                    $entry = $part->getArray();
                    $entry['name'] = $part->getType()->getName();
                    $data['parts'][] = $entry;

                }
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
