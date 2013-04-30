<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');

// Vérifie que l'utilisateur est bien connecét.
if (Account::isAuthenticated()) {

    // Déconnecter l'utilisateur.
    Account::Logout();

    // Redirige l'utilisateur à la page de connexion.
    header('location: index.php');
}