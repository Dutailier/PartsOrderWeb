<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/categories.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'A cateogry must be selected.';
    } else if (empty($_GET['serial'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';
    } else {

        try {
            $cart = new SessionCart();

            $data['parts'] = array();
            $category = Categories::Find($_GET['categoryId']);
            foreach ($category->getParts() as $part) {
                $item = new CartItem($part, $category, $_GET['serial']);

                $entry = $part->getArray();
                $entry['quantity'] = $cart->getQuantity($item);

                $data['parts'][] = $entry;
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