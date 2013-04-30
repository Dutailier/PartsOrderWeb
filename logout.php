<?php

include_once('config.php');
include_once(ROOT . 'libs/user.php');

// Vérifie que l'utilisateur est bien connecét.
if (User::isAuthenticated()) {

    // Déconnecter l'utilisateur.
    User::Logout();

    // Redirige l'utilisateur à la page de connexion.
    header('location: index.php');
}