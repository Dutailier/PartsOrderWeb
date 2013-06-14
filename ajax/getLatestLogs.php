<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/logs.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::isInRoleName(ROLE_ADMINISTRATOR)) {
    $data['success'] = false;
    $data['message'] = 'You must be connected as administrator.';

} else {
    try {
        $logs = Logs::Top(10);

        $data['logs'] = array();
        foreach ($logs as $log) {
            $array = $log->getArray();
            $array['order'] = $log->getOrder()->getArray();

            $data['logs'][] = $array;
        }

        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);