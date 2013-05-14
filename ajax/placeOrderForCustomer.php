<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/roles.php');
include_once(ROOT . 'libs/repositories/orderHeaders.php');
include_once(ROOT . 'libs/repositories/retailers.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/customers.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else if (!Roles::IsInRoleName('retailer')) {
    $data['success'] = false;
    $data['message'] = 'You must be logged as a retailer.';

} else {
    // Validation des informations passées en POST.
    if (empty($_POST['firstname'])) {
        $data['success'] = false;
        $data['message'] = 'The first name is required.';
    } else if (empty($_POST['lastname'])) {
        $data['success'] = false;
        $data['message'] = 'The last name is required.';
    } else if (empty($_POST['email'])) {
        $data['success'] = false;
        $data['message'] = 'The email name is required.';
    } else if (empty($_POST['address'])) {
        $data['success'] = false;
        $data['message'] = 'The address name is required.';
    } else if (empty($_POST['city'])) {
        $data['success'] = false;
        $data['message'] = 'The city name is required.';
    } else if (empty($_POST['zip'])) {
        $data['success'] = false;
        $data['message'] = 'The zip name is required.';
    } else if (empty($_POST['stateId'])) {
        $data['success'] = false;
        $data['message'] = 'The state name is required.';
    } else if (empty($_POST['countryId'])) {
        $data['success'] = false;
        $data['message'] = 'The country name is required.';
    } else {
        try {
            $retailer = Retailers::getConnected();

            $address = Addresses::Add(
                $_POST['address'],
                $_POST['city'],
                $_POST['zip'],
                $_POST['stateId']);

            $customer = Customers::Add(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['phone'],
                $_POST['email'],
                $address->getId());

            $orderHeaderId = OrderHeaders::Add($retailer->getId(), $customer->getAddressId(), $customer->getId());

            $cart = new SessionCart;

            foreach ($cart->getItems() as $item) {
                OrderLines::Add(
                    $orderHeaderId,
                    $item->getPartId(),
                    $item->getSerialGlider(),
                    $item->getQuantity());
            }

            $cart->clear();
            $data['orderHeaderId'] = $orderHeaderId;
            $data['success'] = true;

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);