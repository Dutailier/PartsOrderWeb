<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/customer.php');

class Customers
{
    public static function Attach(Customer $customer)
    {
        $query = 'EXEC [addCustomer]';
        $query .= '@firstname = "' . $customer->getFirstname() . '", ';
        $query .= '@lastname = "' . $customer->getLastname() . '", ';
        $query .= '@phone = "' . $customer->getPhone() . '", ';
        $query .= '@email = "' . $customer->getEmail() . '", ';
        $query .= '@addressId = "' . $customer->getAddressId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The address wasn\'t added.');
        }

        $customer->setId($rows[0]['id']);

        return $customer;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getCustomerById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No customer found.');
        }

        $customer = new Customer(
            $rows[0]['firstname'],
            $rows[0]['lastname'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']
        );
        $customer->setId($rows[0]['id']);

        return $customer;
    }
}