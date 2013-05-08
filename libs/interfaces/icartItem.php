<?php

include_once('config.php');
include_once(ROOT . 'libs/interfaces/icartItem.php');
include_once(ROOT . 'libs/interfaces/icomparable.php');

interface ICartItem extends IComparable
{
    public function getQuantity();

    public function setQuantity($quantity);
}