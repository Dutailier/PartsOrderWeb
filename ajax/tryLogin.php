<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');

if (empty($_POST['username']) || empty($_POST['password'])) {
    $data['success'] = false;
    $data['message'] = 'Username and password are required.';

} else {

    try {
        $data['success'] = Security::TryLogin($_POST['username'], $_POST['password']);

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);