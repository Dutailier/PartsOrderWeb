<?php

require_once 'database.php';

// Vérifie si les informations ont bien été passées.
if (empty($_POST['username']) || empty($_POST['password'])) {
    $data['success'] = false;
    $data['message'] = 'Username or password incorrect.';

} else {

    // Récupère les informations passées.
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    // Chiffre le mot de passe en encryptant la concaténation du
    // mot de passe et du nom d'utilisateur (grain de sel).
    $password = sha1($password . $username);

    // Récupère la connexion à la base de données.
    $conn = database::getConnection();

    if (empty($conn)) {
        $data['success'] = false;
        $data['message'] = 'The connection to the database failed.';
    } else {

        // Exécute la procédure stockée.
        $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[tryLogin]("' . $username . '", "' . $password . '")}');

        if (empty($result)) {
            $data['success'] = false;
            $data['message'] = 'The execution of the query failed.';
        } else {

            // Récupère la première ligne résultante.
            $row = odbc_fetch_row($result);

            if (empty($row)) {
                $data['success'] = false;
                $data['message'] = 'Username or password incorrect.';
            } else {

                // La connexion est un succès.
                $data['success'] = true;
                $data['role_name'] = $row['role_name'];

				// Démarre une session si celle-ci n'est pas déjà active.
				if (!$_SESSION) {
					session_start();
				}

                // Stocke les informations de l'utilisateur pour la durée de la session.
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role_name'] = $row['role_name'];
                $_SESSION['retailer_name'] = $row['retailier_name'];
                $_SESSION['username'] = $username;
            }
        }
    }
}
// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);