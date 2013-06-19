<?php

interface ITransaction
{
    public function getDestination();

    public function getCategory();

    public function getShippingAddress();

    public function getStore();

    public function getReceiver();

    public function getStatus();
}