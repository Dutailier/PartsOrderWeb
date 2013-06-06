<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/sessionTransaction.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    try {
        $cart = new SessionCart();

        if ($cart->isEmpty()) {
            $data['success'] = false;
            $data['message'] = 'You must have at least one item in your shopping cart.';
        } else {
            $transaction = new SessionTransaction();
            $transaction->Proceed();

            $data['success'] = true;
        }

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);
