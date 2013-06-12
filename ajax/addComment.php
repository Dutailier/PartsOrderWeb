<?php

include_once('../config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/comments.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';

} else {
    if (empty($_POST['orderId'])) {
        $data['success'] = false;
        $data['message'] = 'A order must be selected.';

    } else if (empty($_POST['text'])) {
        $data['success'] = false;
        $data['message'] = 'A text is required.';

    } else {
        try {
            $user = Security::getUserConnected();
            $order = Orders::Find($_POST['orderId']);
            $store = $order->getStore();

            if (!Security::isInRoleName(ROLE_ADMINISTRATOR) && $user->getId() != $store->getUserId()) {
                $data['success'] = false;
                $data['message'] = 'You must be at the origin of the order.';

            } else {
                Comments::Attach(new Comment(
                    $order->getId(),
                    $user->getId(),
                    $_POST['text']
                ));

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