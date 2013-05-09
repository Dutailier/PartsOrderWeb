<?php

include_once('config.php');
include_once(ROOT . 'libs/repositories/types.php');

/**
 * Class Part
 * Représente une pièce.
 */
class Part
{
    private $id;
    private $sku;
    private $serialGlider;
    private $typeId;

    public function __construct($id, $serialGlider, $typeId, $sku = null)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->serialGlider = $serialGlider;
        $this->typeId = $typeId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSerialGlider()
    {
        return $this->serialGlider;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getType()
    {
        return Types::Find($this->typeId);
    }
}
