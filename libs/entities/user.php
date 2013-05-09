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
    public function __construct($id, $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername());
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
}