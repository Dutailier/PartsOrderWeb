<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/stores.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::isInRoleName(ROLE_ADMINISTRATOR)) {
    $data['success'] = false;
    $data['message'] = 'You must be connected as administrator.';

} else {
    if (empty($_POST['bannerId'])) {
        $data['success'] = false;
        $data['message'] = 'A banner must be selected.';

    } else {
        try {
            $stores = Stores::FilterByBannerId($_POST['bannerId']);

            $data['stores'] = array();
            foreach ($stores as $store) {
                $data['stores'][] = $store->getArray();
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