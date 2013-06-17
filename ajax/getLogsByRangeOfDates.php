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
    if (empty($_POST['from']) || empty($_POST['to'])) {
        $data['success'] = true;
        $data['message'] = 'A rage of dates is required.';

    } else {
        try {
            $logs = Logs::FilterByRangeOfDates(strtotime($_POST['from']), strtotime($_POST['to']));

            $data['logs'] = array();
            foreach ($logs as $log) {
                $data['logs'][] = $log->getArray();
            }

            $data['success'] = true;
        } catch
        (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);