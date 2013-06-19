<?php

interface ITransaction
{
    public function getDestination();

    public function getType();

    public function getShippingAddress();

    public function getStore();

    public function getReceiver();

    public function getStatus();
}