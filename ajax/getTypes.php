<?php

include_once('../config.php');
include_once(ROOT . 'libs/models/type.php');
include_once(ROOT . 'libs/cart.php');
include_once(ROOT . 'libs/cartItem.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    $data['success'] = false;
    $data['message'] = 'You must be authenticated.';
} else {

    if (empty($_GET['categoryId'])) {
        $data['success'] = false;
        $data['message'] = 'A cateogry must be selected.';
    } else if (empty($_GET['serialGlider'])) {
        $data['success'] = false;
        $data['message'] = 'The serial is required.';
    } else {

        try {
            $cart = new SessionCart();

            $types = array();
            $category = new Category($_GET['categoryId']);
            foreach ($category->getTypes() as $type) {
                $item = new CartItem($type, $_GET['serialGlider']);

                $data['types'][] = array(
                    'id' => $type->getId(),
                    'name' => $type->getName(),
                    'description' => $type->getDescription(),
                    'quantity' => $cart->getQuantity($item)
                );
                $data['success'] = true;
            }

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