<?php

include_once('../config.php');
include_once(ROOT . 'libs/models/retailer.php');
include_once(ROOT . 'libs/models/address.php');
include_once(ROOT . 'libs/models/state.php');
include_once(ROOT . 'libs/models/customer.php');
include_once(ROOT . 'libs/models/order.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/file.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
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
            $retailer = Retailer::getConnected();

            $address = Address::Add(
                $_POST['address'],
                $_POST['city'],
                $_POST['zip'],
                new State($_POST['stateId']));

            $customer = Customer::Add(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['phone'],
                $_POST['email'],
                $address);

            $order = Order::Place($retailer, $customer);

            $cart = new SessionCart;

            foreach ($cart->getItems() as $item) {
                $order->AddItem($item);
            }

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