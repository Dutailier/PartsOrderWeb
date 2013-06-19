<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/categories.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'A category must be selected.';

    } else {
        try {
            $transaction = new SessionTransaction();

            $category = Categories::Find($_POST['categoryId']);
            $transaction->setCategory($category);

            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);