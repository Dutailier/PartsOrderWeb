<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

interface ICartItem extends IComparable
{
    public function setQuantity($quantity);
    public function getQuantity();
}