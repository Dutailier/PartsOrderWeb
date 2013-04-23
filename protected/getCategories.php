<?php

require_once 'database.php';

// Récupère la connexion à la base de données.
$conn = database::getConnection();

if(empty($conn)) {
	$data['success'] = false;
	$data['message'] = 'The connection to the database failed.';
} else {
	
	// Exécute la procédure stockée.
	$result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCategories]}');
	
	if(empty($result)) {
		$data['success'] = false;
		$data['message'] = 'The execution of the query failed.';
	} else {
	
		// Récupère la nombre de lignes résultantes.
		$count = odbc_num_rows($result);
		
		if(empty($count)) {
			$data['success'] = false;
			$data['message'] = 'No categories available.';
		} else {
			
			// La requête est un succès.
			$data['success'] = true;
			
			// Récupère le nombre de ligne pour de futures utilisations.
			$data['lenght'] = $count;
			
			// Inscrire chaque ligne dans l'objet JSON qui sera retourné.
			for($i = 0; $i < $data['lenght']; $i++) {
				odbc_fetch_row($result);
				$data[$i]['category_id'] = odbc_result($result, 'category_id');
				$data[$i]['category_name'] = odbc_result($result, 'category_name');
			}
		}
	}	
}

// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);

