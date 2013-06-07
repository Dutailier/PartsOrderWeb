<?php

include_once('config.php');
include_once(ROOT . 'libs/item.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/filters.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');
include_once(ROOT . 'libs/interfaces/itransaction.php');

class SessionTransaction implements ITransaction
{
    const CART_IDENTIFIER = '_CART_';
    const DEFAULT_FILTER_IDENTIFIER = '_DEFAULT_FILTER_';
    const SHIPPING_ADDRESS_IDENTIFIER = '_SHIPPING_ADDRESS_';
    const STORE_IDENTIFIER = '_STORE_';
    const RECEIVER_IDENTIFIER = '_RECEIVER_';
    const ORDER_IDENTIFIER = '_ORDER_';
    const LINES_IDENTIFIER = '_LINES_';

    public function __construct()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION[self::CART_IDENTIFIER])) {
            $_SESSION[self::CART_IDENTIFIER] = new SessionCart();
        }

        if (!isset($_SESSION[self::LINES_IDENTIFIER])) {
            $_SESSION[self::LINES_IDENTIFIER] = array();
        }
    }


    public function Copy(ITransaction $transaction)
    {
        $_SESSION[self::DEFAULT_FILTER_IDENTIFIER] = $transaction->getDefaultFilter();
        $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER] = $transaction->getShippingAddress();
        $_SESSION[self::STORE_IDENTIFIER] = $transaction->getStore();
        $_SESSION[self::RECEIVER_IDENTIFIER] = $transaction->getReceiver();
    }


    public function getArray()
    {
        $array = array();

        if ($this->isOpen()) {
            $array['shippingAddress'] = $this->getShippingAddress()->getArray();
            $array['store'] = $this->getStore()->getArray();
            $array['receiver'] = $this->getReceiver()->getArray();
        }

        if ($this->isProceed()) {
            $array['order'] = $this->getOrder()->getArray();

            foreach ($this->getLines() as $line) {
                $array['lines'][] = $line->getArray();
            }
        }
        return $array;
    }


    public function Open(Address $shippingAddress, Store $store, Receiver $receiver)
    {
        $this->setShippingAddress($shippingAddress);
        $this->setStore($store);
        $this->setReceiver($receiver);
    }

    public function Proceed()
    {
        $address = Addresses::Attach($this->getShippingAddress());
        $receiver = Receivers::Attach($this->getReceiver());

        $order = new Order(
            $address->getId(),
            $this->getStore()->getId(),
            $receiver->getId()
        );

        $_SESSION[self::ORDER_IDENTIFIER] = Orders::Attach($order);

        $_SESSION[self::LINES_IDENTIFIER] = array();
        foreach ($this->getCart()->getItems() as $item) {
            $line = new Line(
                $_SESSION[self::ORDER_IDENTIFIER]->getId(),
                $item->getProduct()->getId(),
                $item->getQuantity(),
                $item->getSerial()
            );

            $_SESSION[self::LINES_IDENTIFIER][] = Lines::Attach($line);
        }
    }

    public function Destroy()
    {
        unset($_SESSION[self::DEFAULT_FILTER_IDENTIFIER]);
        unset($_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER]);
        unset($_SESSION[self::STORE_IDENTIFIER]);
        unset($_SESSION[self::RECEIVER_IDENTIFIER]);
        unset($_SESSION[self::ORDER_IDENTIFIER]);
        unset($_SESSION[self::LINES_IDENTIFIER]);
        $_SESSION[self::CART_IDENTIFIER]->Clear();
    }

    public function setDefaultFilter(Filter $filter)
    {
        if (!$filter->isAttached()) {
            throw new Exception('The filter must be attached to a database.');
        }

        $_SESSION[self::DEFAULT_FILTER_IDENTIFIER] = $filter;
    }

    public function getDefaultFilter()
    {
        if (!$this->isOpen()) {
            throw new Exception('The filter must be previously setted.');
        }

        return $_SESSION[self::DEFAULT_FILTER_IDENTIFIER];
    }


    public function setShippingAddress(Address $address)
    {
        if ($address->isAttached()) {
            throw new Exception('The address can\'t be attached to a database.');
        }

        $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER] = $address;
    }


    public function getShippingAddress()
    {
        if (!$this->isOpen()) {
            throw new Exception('The shipping address must be previously setted.');
        }

        return $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER];
    }


    public function setStore(Store $store)
    {
        if (!$store->isAttached()) {
            throw new Exception('The store must be attached to a database.');
        }

        $_SESSION[self::STORE_IDENTIFIER] = $store;
    }


    public function getStore()
    {
        if (!$this->isOpen()) {
            throw new Exception('The store must be previously setted.');
        }

        return $_SESSION[self::STORE_IDENTIFIER];
    }


    public function setReceiver(Receiver $receiver)
    {
        if ($receiver->isAttached()) {
            throw new Exception('The address can\'t be attached to a database.');
        }

        $_SESSION[self::RECEIVER_IDENTIFIER] = $receiver;
    }


    public function getReceiver()
    {
        if (!$this->isOpen()) {
            throw new Exception('The receiver must be previously setted.');
        }

        return $_SESSION[self::RECEIVER_IDENTIFIER];
    }

    private function getCart()
    {
        return $_SESSION[self::CART_IDENTIFIER];
    }


    public
    function AddItem(IItem $item)
    {
        return $this->getCart()->Add($item);
    }


    public function RemoveItem(IItem $item)
    {
        return $this->getCart()->Remove($item);
    }


    public function getOrder()
    {
        if (!$this->isProceed()) {
            throw new Exception('The transaction must be previously executed.');
        }

        return $_SESSION[self::ORDER_IDENTIFIER];
    }


    public function getLines()
    {
        if (!$this->isProceed()) {
            throw new Exception('The transaction must be previously executed.');
        }

        return $_SESSION[self::LINES_IDENTIFIER];
    }

    public function isOpen()
    {
        return
            isset($_SESSION[self::DEFAULT_FILTER_IDENTIFIER]) &&
            isset($_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER]) &&
            isset($_SESSION[self::RECEIVER_IDENTIFIER]) &&
            isset($_SESSION[self::STORE_IDENTIFIER]);
    }

    public function isProceed()
    {
        return
            isset($_SESSION[self::ORDER_IDENTIFIER]) &&
            isset($_SESSION[self::LINES_IDENTIFIER]);
    }
}