<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');
include_once(ROOT . 'libs/repositories/destinations.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['destinationId'])) {
        $data['success'] = false;
        $data['message'] = 'A destination is required.';

    } else {
        try {
            $transaction = new SessionTransaction();

            $destination = Destinations::Find($_POST['destinationId']);
            $customerInfosAreRequired = $destination->getId() == DESTINATION_TO_GUEST;

            if (!$customerInfosAreRequired) {
                $user = Security::getUserConnected();
                $store = $user->getStore();
                $address = $store->getAddress();

                $receiver = new Receiver(
                    $store->getName(),
                    $store->getPhone(),
                    $store->getEmail()
                );
            }

            // On définit la destination et les informations d'expéditions ici
            // au cas où une erreur serait levée.
            $transaction->setDestination($destination);

            if (!$customerInfosAreRequired) {
                $transaction->setShippingInfos($address, $store, $receiver);
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