<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/roles.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/retailers.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Roles::IsInRoleName('retailer')) {
    $data['success'] = false;
    $data['message'] = 'You must be logged as a retailer.';

} else {
    try {
        $retailer = Retailers::getConnected();

        $order = Orders::Add($retailer->getId());

        $cart = new SessionCart();

        foreach ($cart->getItems() as $item) {
            Parts::Add(
                $item->getTypeId(),
                $item->getSerialGlider(),
                $item->getQuantity(),
                $order->getId());
        }

        $cart->clear();
        $data['orderId'] = $order->getId();
        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);