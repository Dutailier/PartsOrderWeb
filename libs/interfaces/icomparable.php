<?php

/**
 * Class IComparable
 * Définit les méthodes nécessaires pour rendre un objet comparable.
 */
interface IComparable
{
    /**
     * Retourne vrai si l'objet passé est égale à l'objet courant.
     * @param $object
     * @return mixed
     */
    public function Equals($object);
}