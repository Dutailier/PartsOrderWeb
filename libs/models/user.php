<?php

include_once('config.php');

/**
 * Class User
 * Gère les méthodes relatives aux utilisateurs.
 */
class User
{
    private $id;
    private $username;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $username
     */
    public function __construct($id, $username = null)
    {
        $this->id = $id;
        $this->username = $username;
    }

    /**
     * Retourne l'instance de l'utilisateur connecté.
     * @return mixed
     */
    public static function getConnected()
    {
        return $_SESSION['user'];
    }

    /**
     * Retourne l'id de l'utilisateur.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom d'utilisateur de l'utilisateur.
     * @return mixed
     */
    public function getUserName()
    {
        return $this->username;
    }
}