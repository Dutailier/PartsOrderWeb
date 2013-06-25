<?php

/**
 * Class Entity
 * Représente un entité de la base de donnée.
 */
abstract class Entity
{
    private $id;
    private $isAttached;

    /**
     * Retourne un tableau contenant des informations de l'entité.
     * @return mixed
     */
    public abstract function getArray();

    /**
     * Définit l'identifiant de l'entité.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->isAttached = true;
    }

    /**
     * Retourne l'identitifant de l'entité.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne vrai si l'entité est attaché à une base de données.
     * @return mixed
     */
    public function isAttached()
    {
        return $this->isAttached;
    }

    /**
     * Détache l'entité de la base de données.
     */
    public function Detach()
    {
        $this->id = null;
        $this->isAttached = false;
    }
}