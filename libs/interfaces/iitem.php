<?php

include_once(ROOT . 'libs/interfaces/icomparable.php');

interface IItem extends IComparable
{
    public function getQuantity();

    public function setQuantity($quantity);
}