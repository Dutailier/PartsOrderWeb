<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/roles.php');

/**
 * Class Role
 * Gère les méthodes relatives aux rôles des utilisteurs.
 */
class Role
{
    private $id;
    private $name;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
        );
    }

    /**
     * Retourne l'id du rôle.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom du rôle.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}