<?php

include_once('../config.php');

include_once(ROOT . 'libs/security.php');
if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_POST['productId'])) {
        $data['success'] = false;
        $data['message'] = 'A product must be selected.';
    } else if (empty($_POST['serial'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';
    } else {

        try {
            include_once(ROOT . 'libs/repositories/products.php');
            $product = Products::Find($_POST['productId']);

            include_once(ROOT . 'libs/entities/product.php');
            $item = new Item($product, $_POST['serial']);

            include_once(ROOT . 'libs/sessionTransaction.php');
            $transaction = new SessionTransaction();

            $data['quantity'] = $transaction->RemoveItem($item);
            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);