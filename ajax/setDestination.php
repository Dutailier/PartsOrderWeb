<?php

include_once('../config.php');

include_once(ROOT . 'libs/security.php');
if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['filterId'])) {
        $data['success'] = false;
        $data['message'] = 'A destination is required.';

    } else {
        try {
            include_once(ROOT . 'libs/sessionTransaction.php');
            $transaction = new SessionTransaction();

            include_once(ROOT . 'libs/repositories/filters.php');
            $filter = Filters::Find($_POST['filterId']);
            $transaction->setDefaultFilter($filter);

            $customerInfosAreRequired = $filter->getId() == FILTER_TO_GUEST_ID;

            if (!$customerInfosAreRequired) {
                $user = Security::getUserConnected();
                $store = $user->getStore();
                $address = $store->getAddress();
                $address->Detach();

                include_once(ROOT . 'libs/entities/receiver.php');
                $receiver = new Receiver(
                    $store->getName(),
                    $store->getPhone(),
                    $store->getEmail()
                );

                $transaction->Open($address, $store, $receiver);
            }

            $data['customerInfosAreRequired'] = $customerInfosAreRequired;
            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);