<?php

include_once('../config.php');

include_once(ROOT . 'libs/security.php');
if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_POST['countryId'])) {
        $data['success'] = false;
        $data['message'] = 'A country must be selected.';
    } else {

        try {
            $data['states'] = array();

            include_once(ROOT . 'libs/repositories/states.php');
            foreach (States::FilterByCountryId($_POST['countryId']) as $state) {
                $data['states'][] = $state->getArray();
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