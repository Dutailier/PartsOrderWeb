<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/types.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['typeId'])) {
        $data['success'] = false;
        $data['message'] = 'A type is required.';

    } else {
        try {
            $transaction = new SessionTransaction();

            $type = Types::Find($_POST['typeId']);
            $transaction->setType($type);

            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);