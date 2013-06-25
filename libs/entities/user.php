<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/roles.php');
include_once(ROOT . 'libs/repositories/stores.php');

/**
 * Class User
 * Représente un utilisateur.
 */
class User extends Entity
{
    private $username;

    /**
     * Initialise l'utilisateur.
     * @param $username
     */
    function __construct($username)
    {
        $this->setUsername($username);
    }


    /**
     * Retourne un tableau contenant les informations de l'utilisateur.
     * @return array|mixed
     */
    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername()
        );
    }


    /**
     * Retourne le nom d'utilisateur.
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Définit le nom d'utilisteur.
     * @param $username
     * @throws Exception
     */
    private function setUsername($username)
    {
        if(strlen($username) > 20) {
            throw new Exception('The length of the username is too long.');
        }

        $this->username = trim($username);
    }


    /**
     * Retourne les rôles de l'utilisateur.
     * @return array
     */
    public function getRoles()
    {
        return Roles::FilterByUserId($this->getId());
    }


    /**
     * Retourne le premier magasin de l'utilisateur.
     * @return mixed
     */
    public function getStore()
    {
        return Stores::FilterByUserId($this->getId())[0];
    }
}