<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

class Order
{
    private $id;
    private $retailer_id;
    private $customer_id;
    private $parts;

    public function Place(Retailer $retailer = null, Customer $customer = null)
    {
    }
}