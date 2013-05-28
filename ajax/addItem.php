<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/products.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['productId'])) {
        $data['success'] = false;
        $data['message'] = 'A item must be selected.';
    } else if (empty($_GET['serial'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';
    } else {

        try {
            $product = Products::Find($_GET['productId']);
            $item = new Item($product, $_GET['serial']);

            $cart = new SessionCart();
            $data['success'] = true;
            $data['quantity'] = $cart->Add($item);

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);
