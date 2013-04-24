<?php

// Démarre la session si cela n'est pas déjà fai.
if(!isset($_SESSION)) {
    session_start();
}

// Si l'utilisateur n'est pas authentifié, on le redirige
// vers la page de connexion.
if (!$_SESSION['authenticated']) {
    header('location: index.php');
}