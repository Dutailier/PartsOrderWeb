<?php

include_once('../config.php');
include_once(ROOT . 'libs/user.php');

// Vérifie si les informations ont bien été passées.
if (empty($_POST['username']) || empty($_POST['password'])) {
    $data['success'] = false;
    $data['message'] = 'Username or password incorrect.';

} else {

    try {
        // Essaie de connecter l'utilisateur.
        $data['success'] = User::TryLogin($_POST['username'], $_POST['password']);
    } catch (Exception $e) {

        // Si la connexion échoué, retourne un message d'erreur.
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }
}
// Indique que le contenu de la page affichera un objet JSON.
header('Content-type: application/json');

// Affiche l'objet JSON.
echo json_encode($data);