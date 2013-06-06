<?php

interface ITransaction
{
    public function Copy(ITransaction $transaction);

    public function getArray();

    public function Open(Address $shippingAddress, Store $store, Receiver $receiver);

    public function Proceed();

    public function Close();

    public function Destroy();

    public function setDefaultFilter(Filter $filter);

    public function getDefaultFilter();

    public function setShippingAddress(Address $address);

    public function getShippingAddress();

    public function setStore(Store $store);

    public function getStore();

    public function setReceiver(Receiver $receiver);

    public function getReceiver();

    public function AddItem(IItem $item);

    public function RemoveItem(IItem $item);

    public function getOrder();

    public function getLines();

    public function isOpen();
}