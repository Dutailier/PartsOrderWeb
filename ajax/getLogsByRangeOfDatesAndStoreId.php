<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/logs.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['from']) || empty($_POST['to'])) {
        $data['success'] = true;
        $data['message'] = 'A rage of dates is required.';

    } else if (empty($_POST['storeId'])) {
        $data['success'] = false;
        $data['message'] = 'A store must be selected.';

    } else {
        try {
            $user = Security::getUserConnected();
            $store = Stores::Find($_POST['storeId']);

            if (!Security::isInRoleName(ROLE_ADMINISTRATOR) && $user->getId() != $store->getUserId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';

            } else {
                $logs = Logs::FilterByRangeOfDatesAndStoreId(
                    strtotime($_POST['from']),
                    strtotime($_POST['to']),
                    $store->getId());

                $data['logs'] = array();
                foreach ($logs as $log) {
                    $data['logs'][] = $log->getArray();
                }

                $data['success'] = true;
            }
        } catch
        (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);