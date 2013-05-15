<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/roles.php');
include_once(ROOT . 'libs/repositories/retailers.php');

/**
 * Class Account
 * Gère les méthodes relatives à la sécurité du site web.
 */
class Security
{
    const USER_IDENTIFIER = '_USER_';
    const RETAILER_IDENTIFIER = '_RETAILER_';
    const ROLES_IDENTIFIER = '_ROLES_';

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
        // Le chiffrement du mot de passe est composé de la
        // concaténation du mot de passe inscrit par l'utilisateur
        // et du nom d'utilisateur (grain de sel).
        $username = strtolower($username);
        $password = sha1($password . $username);

        try {
            $user = Users::FindByUsernameAndPassword($username, $password);

            // Si une exception es levée, c'est qu'aucun utilisateur n'a été trouvé.
        } catch (Exception $e) {
            return false;
        }

        if (session_id() == '') {
            session_start();
        }

        $_SESSION[self::USER_IDENTIFIER] = $user;

        return true;
    }

    /**
     * Retourne le retailer présentement connecté.
     * @return mixed
     * @throws Exception
     */
    public static function getRetailerConnected()
    {
        if (!self::isInRoleName('Retailer')) {
            throw new Exception('You must be connected as retailer.');
        }

        if (empty($_SESSION[self::RETAILER_IDENTIFIER])) {
            $user = self::getUserConnected();
            $retailer = Retailers::FindByUserId($user->getId());
            $_SESSION[self::RETAILER_IDENTIFIER] = $retailer;
        }

        return $_SESSION[self::RETAILER_IDENTIFIER];
    }

    /**
     * Retourne vrai si l'utilisateur détient se rôle.
     * @param $name
     * @return bool
     * @throws Exception
     */
    public static function isInRoleName($name)
    {
        if (!self::isAuthenticated()) {
            throw new Exception('You must be authenticated.');
        }

        if (empty($_SESSION[self::ROLES_IDENTIFIER])) {
            $user = self::getUserConnected();
            $roles = Roles::FilterByUserId($user->getId());
            $_SESSION[self::ROLES_IDENTIFIER] = $roles;
        }

        foreach ($_SESSION[self::ROLES_IDENTIFIER] as $role) {
            if (strtolower($role->getName()) == strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retourne vrai si l'utilisateur s'est authentifié.
     * @return bool
     */
    public static function isAuthenticated()
    {
        if (session_id() == '') {
            session_start();
        }

        return !empty($_SESSION[self::USER_IDENTIFIER]);
    }

    /**
     * Retourne l'utilisateur présentement connecté.
     * @return mixed
     * @throws Exception
     */
    public static function getUserConnected()
    {
        if (!self::isAuthenticated()) {
            throw new Exception('You must be authenticated.');
        }

        return $_SESSION[self::USER_IDENTIFIER];
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function Logout()
    {
        if (!self::isAuthenticated()) {
            throw new Exception('You must be connected.');
        }

        session_destroy();
    }
}