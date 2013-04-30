<?php

include_once('../config.php');
include_once(ROOT . 'libs/database.php');

// Récupère la connexion à la base de données.
$conn = database::getConnection();

if (empty($conn)) {
    $data['success'] = false;
    $data['message'] = 'The connection to the database failed.';
} else {

    // Exécute la procédure stockée.
    $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCategories]}');

    if (empty($result)) {
        $data['success'] = false;
        $data['message'] = 'The execution of the query failed.';
    } else {

        // La requête est un succès.
        $data['success'] = true;

        $i = 0;
        // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
        while (odbc_fetch_row($result)) {
            $data['categories'][$i]['category_id'] = odbc_result($result, 'category_id');
            $data['categories'][$i]['category_name'] = odbc_result($result, 'category_name');
            $i++;
        }
    }
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);

