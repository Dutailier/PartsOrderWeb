<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/filters.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/customers.php');

class Transaction
{
    const IDENTIFIER = '_TRANSACTION_';
    private $shippingAddress;
    private $retailer;
    private $customer;
    private $order;
    private $destinationFilter;
    private $lines;
    private $customerInfosAreRequired;

    private function __construct()
    {
        $this->retailer = Security::getRetailerConnected();
    }

    public static function getCurrent()
    {
        if (session_id() == '') {
            session_start();
        }

        if (empty($_SESSION[self::IDENTIFIER])) {
            $_SESSION[self::IDENTIFIER] = new Transaction();
        }

        return $_SESSION[self::IDENTIFIER];
    }

    public function getArray()
    {
        $infos = array(
            'shippingAddress' => $this->getShippingAddress()->getArray(),
            'retailer' => $this->getRetailer()->getArray()
        );

        if (!is_null($this->order)) {
            $infos['order'] = $this->getOrder()->getArray();
        }

        if (!is_null($this->customer)) {
            $infos['customer'] = $this->getCustomer()->getArray();
        }

        if (!is_null($this->order)) {
            $infos['order'] = $this->getOrder()->getArray();
        }

        if (!is_null($this->lines)) {
            foreach ($this->lines as $line) {
                $infos['lines'][] = $line->getArray();
            }
        }

        return $infos;
    }

    public function setDestinationFilter($filter)
    {
        $this->destinationFilter = $filter;
        $this->customerInfosAreRequired = $filter->getId() == FILTER_TO_GUEST_ID;
    }

    public function getDestinationFilter()
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');
        }

        return $this->destinationFilter;
    }

    public function isOpen()
    {
        return !empty($this->destinationFilter);
    }

    public function setCustomer($firstname, $lastname, $phone, $email, $addressId)
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');
        }

        $customer = new Customer(
            $firstname,
            $lastname,
            $phone,
            $email,
            $addressId
        );

        return $this->customer = Customers::Attach($customer);
    }

    public function getCustomer()
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');

        }

        if (empty($this->customer) && $this->customerInfosAreRequired) {
            throw new Exception('The customer informations are required.');
        }

        return $this->customer;
    }

    public function getRetailer()
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');
        }

        return $this->retailer;
    }

    public function setShippingAddress($details, $city, $zip, $stateId)
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');
        }

        $address = new Address(
            $details,
            $city,
            $zip,
            $stateId
        );

        return $this->shippingAddress = Addresses::Attach($address);
    }

    public function getShippingAddress()
    {
        if (empty($this->destinationFilter)) {
            throw new Exception('The transaction must be open.');
        }

        if (empty($this->shippingAddress)) {
            $this->shippingAddress = $this->retailer->getAddress();
        }

        return $this->shippingAddress;
    }

    private function CreateOrder()
    {
        include_once(ROOT . 'libs/repositories/orders.php');

        $order = new Order(
            $this->getShippingAddress()->getId(),
            $this->getRetailer()->getId(),
            !empty($this->customer) ? $this->getCustomer()->getId() : null
        );

        $this->order = Orders::Attach($order);

        include_once(ROOT . 'libs/sessionCart.php');
        include_once(ROOT . 'libs/repositories/lines.php');

        $cart = new SessionCart();
        foreach ($cart->getItems() as $item) {
            $line = new Line(
                $this->getOrder()->getId(),
                $item->getProduct()->getId(),
                $item->getQuantity(),
                $item->getSerial()
            );
            $this->lines[] = Lines::Attach($line);
        }
        $cart->Clear();
    }

    public function getOrder()
    {
        if (empty($this->order)) {
            $this->CreateOrder();
        }

        return $this->order;
    }

    public function Cancel()
    {
        include_once(ROOT . 'libs/sessionCart.php');

        $cart = new SessionCart();

        if (session_id() == '') {
            session_start();
        }

        $cart->Clear();
        unset($_SESSION[self::IDENTIFIER]);
    }
}