<?php

include_once('config.php');

/**
 * Class Part
 * Représente une pièce.
 */
class Part
{
    private $id;
    private $sku;
    private $serialGlider;
    private $type;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param null $sku
     * @param null $serialGlider
     */
    public function __construct($id, $sku = null, $serialGlider = null, Type $type)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->serialGlider = $serialGlider;
        $this->type = $type;
    }

    /**
     * Retourne l'identifiant de la pièce.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le type de la pièce.
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retourne le SKU de la pièce.
     * @return null
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Retourne le numéro de série de chaise nécessitant cette pièce.
     * @return null
     */
    public function getSerialGlider()
    {
        return $this->serialGlider;
    }
}
