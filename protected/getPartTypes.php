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

            // Récupère la nombre de lignes résultantes.
            $lenght = odbc_num_rows($result);

            if (empty($lenght)) {
                $data['success'] = false;
                $data['message'] = 'No parts available.';
            } else {

                // La requête est un succès.
                $data['success'] = true;

                // Récupère le nombre de ligne pour de futures utilisations.
                $data['lenght'] = $lenght;

                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                for ($i = 0; $i < $data['lenght']; $i++) {
                    odbc_fetch_row($result);
                    $data[$i]['partType_id'] = odbc_result($result, 'partType_id');
                    $data[$i]['partType_name'] = odbc_result($result, 'partType_name');
                    $data[$i]['partType_description'] = odbc_result($result, 'partType_description');

                    $data[$i]['partType_quantity'] = Cart::getQuantity($serial_glider, $data[$i]['partType_id']);
                }
            }
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);