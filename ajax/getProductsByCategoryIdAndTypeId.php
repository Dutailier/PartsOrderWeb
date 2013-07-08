<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/products.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    if (empty($_POST['typeId'])) {
        $data['success'] = false;
        $data['message'] = 'The type is required.';

    } else if (empty($_POST['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'The category is required.';

    } else {
        try {
            $transaction = new SessionTransaction();
            $products = Products::FilterByCategoryIdAndTypeId(
                $_POST['categoryId'],
                $_POST['typeId']);

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