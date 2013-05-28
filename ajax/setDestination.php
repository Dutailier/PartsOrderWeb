<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/transaction.php');
include_once(ROOT . 'libs/repositories/filters.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['filterId'])) {
        $data['success'] = false;
        $data['message'] = 'A destination is required.';

    } else {
        try {
            $filter = Filters::Find($_POST['filterId']);
            $transaction = Transaction::getCurrent();

            $transaction->setDestinationFilter($filter);

            $data['customerInfosAreRequired'] = $filter->getId() == FILTER_TO_GUEST_ID;
            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);