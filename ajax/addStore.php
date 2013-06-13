<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/addresses.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Security::isInRoleName(ROLE_ADMINISTRATOR)) {
    $data['success'] = false;
    $data['message'] = 'You must be connected as administrator.';

} else {
    if (empty($_POST['bannerId'])) {
        $data['success'] = false;
        $data['message'] = 'The banner must be selected.';
    } else if (empty($_POST['name'])) {
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
            $user = Users::Attach(new User(
                $_POST['username']
            ));

            $address = Addresses::Attach(new Address(
                $_POST['details'],
                $_POST['city'],
                $_POST['zip'],
                $_POST['stateId']
            ));

            $store = Stores::Attach(new Store(
                $_POST['bannerId'],
                $user->getId(),
                $_POST['name'],
                $_POST['phone'],
                $_POST['email'],
                $address->getId()
            ));

            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);