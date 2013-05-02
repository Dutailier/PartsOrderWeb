<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

/**
 * Class Part
 * Représente une pièce qui pourra être commandé dans le panier d'achats.
 */
class Part implements IComparable
{
    private $type;
    private $name;
    private $serial;

    /**
     * Constructeur par défaut.
     * @param $type
     * @param $name
     * @param $serial
     */
    public function __construct($type, $name, $serial)
    {
        $this->type = $type;
        $this->name = $name;
        $this->serial = $serial;
    }

    /**
     * Retourne vrai si l'objet comparé est identique à cette pièce.
     * @param $object
     * @return bool
     */
    public function equals($object)
    {
        return
            $object instanceof self &&
            $this->getType() == $object->getType() &&
            $this->getSerial() == $object->getSerial();
    }

    /**
     * Retourne le type de la pièce.
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retourne le nom de la pièce.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne le numéro de série de la pièce.
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }
}