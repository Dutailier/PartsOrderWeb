<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/users.php');

class Log extends Entity
{
    private $orderId;
    private $userId;
    private $datetime;
    private $event;

    function __construct($orderId, $userId, $event, $datetime = null)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->datetime = $datetime;
        $this->event = $event;
    }

    public function getArray()
    {
        return array(
            'orderId' => $this->getOrderId(),
            'user' => $this->getUser()->getArray(),
            'event' => $this->getEvent()
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

    public function getEvent()
    {
        return $this->event;
    }

    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function getUser()
    {
        return Users::Find($this->getUserId());
    }
}