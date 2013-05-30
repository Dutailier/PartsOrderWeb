<?php

include_once('config.php');
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

    private $wasExecute;
    private $wasOpen;

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
        $array = array(
            'shippingAddress' => $this->getShippingAddress()->getArray(),
            'store' => $this->getStore()->getArray(),
            'receiver' => $this->getReceiver()->getArray()
        );

        if ($this->wasExecute) {
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

        $this->wasOpen = true;
    }

    public function Execute()
    {
        $address = $this->getShippingAddress();

        // L'adresse devra être attachée à la base de données seulement
        // si celle-ci est celle du client. Autrement, l'adresse du magasin
        // est déjà attaché à la base de données.
        if (!$address->isAttached()) {
            Addresses::Attach($address);
        }

        $receiver = Receivers::Attach($this->getReceiver());

        $order = new Order(
            $address->getId(),
            $this->getStore()->getId(),
            $receiver->getId()
        );

        $order = Orders::Attach($order);

        foreach ($this->getCart()->getItems() as $item) {
            $line = new Line(
                $order->getId(),
                $item->getProduct()->getId(),
                $item->getQuantity(),
                $item->getSerial()
            );

            Lines::Attach($line);
        }

        $this->wasExecute = true;
    }

    public function Close()
    {
        $this->getOrder()->Confirm();
        $this->Destroy();
    }


    public function Destroy()
    {
        unset($_SESSION[self::DEFAULT_FILTER_IDENTIFIER]);
        unset($_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER]);
        unset($_SESSION[self::STORE_IDENTIFIER]);
        unset($_SESSION[self::RECEIVER_IDENTIFIER]);
        unset($_SESSION[self::ORDER_IDENTIFIER]);
        unset($_SESSION[self::LINES_IDENTIFIER]);
        $this->wasOpen = false;
        $this->wasExecute = false;

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
        if (empty($_SESSION[self::DEFAULT_FILTER_IDENTIFIER])) {
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
        if (empty($_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER])) {
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
        if (empty($_SESSION[self::STORE_IDENTIFIER])) {
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
        if (empty($_SESSION[self::RECEIVER_IDENTIFIER])) {
            throw new Exception('The receiver must be previously setted.');
        }

        return $_SESSION[self::RECEIVER_IDENTIFIER];
    }

    private function getCart()
    {
        return $_SESSION[self::CART_IDENTIFIER];
    }


    public function AddItem(IItem $item)
    {
        return $this->getCart()->Add($item);
    }


    public function RemoveItem(IItem $item)
    {
        return $this->getCart()->Remove($item);
    }


    public function getOrder()
    {
        if (empty($_SESSION[self::ORDER_IDENTIFIER])) {
            throw new Exception('The transaction must be previously executed.');
        }

        return $_SESSION[self::ORDER_IDENTIFIER];
    }


    public function getLines()
    {
        if (empty($_SESSION[self::LINES_IDENTIFIER])) {
            throw new Exception('The transaction must be previously executed.');
        }

        return $_SESSION[self::LINES_IDENTIFIER];
    }

    public function isOpen(){
        return $this->wasOpen;
    }
}