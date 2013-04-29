<?php

include_once(dirname(__FILE__) . '/item.php');

/**
 * Class Part
 * Représente une pièce qui pourra être commandé dans le panier d'achats.
 */
class Part extends Item
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
     * Retourne vrai si l'item comparé est identique à cette pièce.
     * @param Item $item
     * @return bool|mixed
     */
    public function Compare(Item $item)
    {
        return
            $item instanceof self &&
            $this->getType() == $item->getType() &&
            $this->getSerial() == $item->getSerial();

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
