<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {

    if (empty($_POST['orderId'])) {
        $data['success'] = false;
        $data['message'] = 'A order must be selected.';
    } else {

        try {
            $user = Security::getUserConnected();

            $order = Orders::Find($_POST['orderId']);
            $store = $order->getStore();

            // Si l'utilisateur n'est pas administrateur ou et qu'il n'est pas à l'origine de la commande,
            // il ne doit pas accéder aux informations relatives à celle-ci. Autrement, si l'utilisateur est soit
            // administrateur ou à l'origine de la commande, il peut accéder à ces informations.
            if (!Security::isInRoleName(ROLE_ADMINISTRATOR) && $user->getId() != $store->getUserId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';

            } else {
                $order->Confirm();

                $data['success'] = true;
            }

        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);