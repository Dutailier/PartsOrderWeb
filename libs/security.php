<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/Users.php');

/**
 * Class Account
 * Gère les méthodes relatives à la sécurité du site web.
 */
class Security
{
    /**
     * Tente de connecté l'utilisateur.
     * Retourne vrai si la connexion réussie.
     * @param $username
     * @param $password
     * @return bool
     * @throws Exception
     */
    public static function TryLogin($username, $password)
    {
        // Traitement du nom d'utilisateur et du mot de passe.
        $username = strtolower($username);
        $password = sha1($password . $username);

        $user = Users::FindByUsernameAndPassword($username, $password);

        if(empty($user)) {
            return false;
        }

        // Démarre la session si cela n'est pas déjà fait.
        if (session_id() == '') {
            session_start();
        }

        $_SESSION['user'] = $user;

        return true;
    }

    /**
     * Retourne vrai si l'utilisateur s'est authentifié.
     * @return bool
     */
    public static function isAuthenticated()
    {
        // Démarre la session si cela n'est pas déjà fait.
        if (session_id() == '') {
            session_start();
        }

        return !empty($_SESSION['user']);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function Logout()
    {
        // Démarre la session si cela n'est pas déjà fait.
        if (session_id() == '') {
            session_start();
        }

        // Détruit la session en cours.
        session_destroy();
    }
}