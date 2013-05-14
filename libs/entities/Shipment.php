<?php

include_once('config.php');

class Shipment
{
    private $id;
    private $addressId;
    private $comments;

    function __construct($id, $addressId, $comments = null)
    {
        $this->id = $id;
        $this->addressId = $addressId;
        $this->comments = $comments;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'addressId' => $this->getAddressId(),
            'comments' => $this->getComments()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getComments()
    {
        return $this->comments;
    }
}