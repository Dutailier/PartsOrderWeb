<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/entities/address.php');
include_once(ROOT . 'libs/entities/receiver.php');
include_once(ROOT . 'libs/sessionTransaction.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['name'])) {
        $data['success'] = false;
        $data['message'] = 'The name is required.';
    } else if (empty($_POST['email'])) {
        $data['success'] = false;
        $data['message'] = 'The email address is required.';
    } else if (empty($_POST['phone'])) {
        $data['success'] = false;
        $data['message'] = 'The phone number is required.';
    } else if (empty($_POST['useStoreAddress'])) {
        $data['success'] = false;
        $data['message'] = 'Should we use the store address?';
    } else if (empty($_POST['details'])) {
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
    } else {
        try {
            $transaction = new SessionTransaction();
            $user = Security::getUserConnected();
            $store = $user->getStore();

            $receiver = new Receiver(
                $_POST['name'],
                $_POST['phone'],
                $_POST['email']
            );

            $shippingAddress = new Address(
                $_POST['details'],
                $_POST['city'],
                $_POST['zip'],
                $_POST['stateId']
            );

            $transaction->setShippingInfos(
                $shippingAddress,
                $store,
                $receiver);

            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);