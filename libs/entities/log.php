<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/users.php');
include_once(ROOT . 'libs/repositories/orders.php');

class Log extends Entity
{
    private $orderId;
    private $username;
    private $datetime;
    private $event;

    function __construct($orderId, $username, $event, $datetime = null)
    {
        $this->orderId = $orderId;
        $this->username = $username;
        $this->datetime = $datetime;
        $this->event = $event;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'order' => $this->getOrder()->getArray(),
            'username' => $this->getUsername(),
            'event' => $this->getEvent(),
            'datetime' => $this->getDatetime()
        );
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getUsername()
    {
        return $this->username;
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

    public function getOrder()
    {
        return Orders::Find($this->getOrderId());
    }
}