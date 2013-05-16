<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::IsInRoleName('retailer')) {
    $data['success'] = false;
    $data['message'] = 'You must be logged as a retailer.';

} else {
    try {
        $cart = new SessionCart();

        if ($cart->isEmpty()) {
            $data['success'] = false;
            $data['message'] = 'You must have at least one item in the shopping cart.';

        } else {
            $retailer = Security::getRetailerConnected();
            $order = Orders::Add($retailer->getId(), $retailer->getAddressId());

            foreach ($cart->getItems() as $item) {
                $order->addLine(
                    $item->getPart()->getId(),
                    $item->getSerial(),
                    $item->getQuantity());
            }
            $cart->clear();

            $data['orderId'] = $order->getId();
            $data['success'] = true;
        }
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);