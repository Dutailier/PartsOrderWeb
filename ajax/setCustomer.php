<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/transaction.php');
include_once(ROOT . 'libs/repositories/addresses.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::IsInRoleName('Retailer')) {
    $data['success'] = false;
    $data['message'] = 'You must be logged as a retailer.';

} else {
    // Validation des informations passées en POST.
    if (empty($_POST['firstname'])) {
        $data['success'] = false;
        $data['message'] = 'The firstname is required.';
    } else if (empty($_POST['lastname'])) {
        $data['success'] = false;
        $data['message'] = 'The lastname is required.';
    } else if (empty($_POST['email'])) {
        $data['success'] = false;
        $data['message'] = 'The email address is required.';
    } else if (empty($_POST['phone'])) {
        $data['success'] = false;
        $data['message'] = 'The phone number is required.';
    } else if (empty($_POST['useStoreAddress'])) {
        $data['success'] = false;
        $data['message'] = 'Should we use the store address?';
    } else if ($_POST['useStoreAddress'] === false) {
        if (empty($_POST['details'])) {
            $data['success'] = false;
            $data['message'] = 'The address is required.';
        } else if (empty($_POST['city'])) {
            $data['success'] = false;
            $data['message'] = 'The city is required.';
        } else if (empty($_POST['zip'])) {
            $data['success'] = false;
            $data['message'] = 'The zip is required.';
        } else if (empty($_POST['stateId'])) {
            $data['success'] = false;
            $data['message'] = 'The state is required.';
        } else if (empty($_POST['countryId'])) {
            $data['success'] = false;
            $data['message'] = 'The country is required.';
        }
    } else {
        try {
            $transaction = Transaction::getCurrent();

            if ($_POST['useStoreAddress'] === false) {
                $address = $transaction->getRetailer()->getAddress();
            } else {
                $address = $transaction->setShippingAddress(
                    $_POST['details'],
                    $_POST['city'],
                    $_POST['zip'],
                    $_POST['stateId']
                );
            }

            $customer = $transaction->setCustomer(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['phone'],
                $_POST['email'],
                $address->getId()
            );

            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);