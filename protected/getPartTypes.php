<?php

require_once 'database.php';
require_once 'cart.php';

if (empty($_GET['category_id']) || empty($_GET['serial_glider'])) {
    $data['success'] = false;
    $data['message'] = 'A cateogry must be selected or a serial number must be entered.';
} else {

    // Récupère les informations passées en GET.
    $serial_glider = $_GET['serial_glider'];
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
                $data['partTypes'][$i]['partType_id'] = $id;
                $data['partTypes'][$i]['partType_name'] = odbc_result($result, 'partType_name');
                $data['partTypes'][$i]['partType_description'] = odbc_result($result, 'partType_description');

                $data['partTypes'][$i]['partType_quantity'] = Cart::getQuantity($serial_glider, $id);
                $i++;
            }
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);