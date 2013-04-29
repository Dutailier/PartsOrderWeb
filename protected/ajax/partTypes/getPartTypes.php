<?php

include_once(dirname(__FILE__) . '/../../libs/database.php');
include_once(dirname(__FILE__) . '/../../libs/cart.php');
include_once(dirname(__FILE__) . '/../../libs/part.php');

if (empty($_GET['category_id']) || empty($_GET['serial'])) {
    $data['success'] = false;
    $data['message'] = 'A cateogry must be selected or a serial number must be entered.';
} else {

    // Récupère les informations passées en GET.
    $serial = $_GET['serial'];
    $category_id = $_GET['category_id'];

    // Récupère la connexion à la base de données.
    $conn = database::getConnection();

    if (empty($conn)) {
        $data['success'] = false;
        $data['message'] = 'The connection to the database failed.';
    } else {

        // Exécute la procédure stockée.
        $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getPartTypes]("' . $category_id . '")}');

        if (empty($result)) {
            $data['success'] = false;
            $data['message'] = 'The execution of the query failed.';
        } else {

            // La requête est un succès.
            $data['success'] = true;

            $i = 0;
            // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
            while (odbc_fetch_row($result)) {
                $id = odbc_result($result, 'partType_id');
                $name = odbc_result($result, 'partType_name');

                $data['partTypes'][$i]['id'] = $id;
                $data['partTypes'][$i]['name'] = $name;
                $data['partTypes'][$i]['description'] = odbc_result($result, 'partType_description');

                $data['partTypes'][$i]['quantity'] = Cart::getQuantity(new Part($id, $name, $serial));
                $i++;
            }
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);