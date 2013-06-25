<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/products.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    if (empty($_POST['serial'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';

    } else {
        try {
            $transaction = new SessionTransaction();
            $categoryId = $transaction->getCategory()->getId();
            $typeId = Products::getTypeIdBySerial($_POST['serial']);
            $products = Products::FilterByCategoryIdAndTypeId($categoryId, $typeId);

            $data['products'] = array();
            foreach ($products as $product) {
                $data['products'][] = $product->getArray();
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