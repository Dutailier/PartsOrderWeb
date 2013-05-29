<?php

include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/roles.php');
include_once(ROOT . 'libs/repositories/stores.php');

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
        if (self::isAuthenticated()) {
            throw new Exception('You can\'t login again.');
        }

        // Le chiffrement du mot de passe est composé de la
        // concaténation du mot de passe inscrit par l'utilisateur
        // et du nom d'utilisateur (grain de sel).
        $username = strtolower($username);
        $password = sha1($password . $username);

        $user = Users::FindByUsernameAndPassword($username, $password);

        $_SESSION[self::USER_IDENTIFIER] = $user;

        return !empty($user);
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
     * Retourne vrai si l'utilisateur inscrit détient le rôle inscrit.
     * @param User $user
     * @param $name
     * @return bool
     */
    public static function UserIsInRole(User $user, $name)
    {
        foreach (Roles::FilterByUserId($user->getId()) as $role) {
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