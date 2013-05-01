<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');

// Vérifie que l'utilisateur est bien connecét.
if (Security::isAuthenticated()) {

    // Déconnecter l'utilisateur.
    Security::Logout();

    // Redirige l'utilisateur à la page de connexion.
    header('location: index.php');
}