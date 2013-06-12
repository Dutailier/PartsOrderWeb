<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/users.php');

class Comment extends Entity
{
    private $orderId;
    private $userId;
    private $text;
    private $creationDate;

    function __construct($orderId, $userId, $text, $creationDate = null)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->text = $text;
        $this->creationDate = $creationDate;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'orderId' => $this->getOrderId(),
            'userId' => $this->getUserId(),
            'text' => $this->getText(),
            'creationDate' => $this->getCreationDate()
        );
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getUser()
    {
        return Users::Find($this->getUserId());
    }

    public function getOrder()
    {
        return Orders::Find($this->getOrderId());
    }
}