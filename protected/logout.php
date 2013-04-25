<?php

// Vérifie si la session n'est pas déjà active.
// Autrement, on la démarre.
if(!$_SESSION) {
    session_start();
}

// Ferme la session en cours.
session_destroy();

// Redirige l'utilisateur à la page de connexion.
header('location: ../index.php');