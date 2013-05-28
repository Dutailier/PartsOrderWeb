<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/filters.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {
    try {
        $destinations = array();
        foreach (Filters::FilterByType(FILTER_DESTINATION) as $filter) {
            $destinations[] = $filter->getArray();
        }

        $data['destinations'] = $destinations;
        $data['success'] = true;

    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}

header('Content-type: application/json');
echo json_encode($data);