<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/customer.php');

class Customers
{
    public static function Add($firstname, $lastname, $phone, $email, $addressId)
    {
        if (!preg_match(Customer::REGEX_PHONE, $phone)) {
            throw new Exception('The phone number must be standard. (i.e. 123-456-7890)');
        } else if (!preg_match(Customer::REGEX_EMAIL, $email)) {
            throw new Exception('The email address must be standard. (i.e. infos@dutailier.com.');
        }
        $phone = preg_replace('[^\d]', '', $phone);
        $phone = trim($phone);
        $phone = (strlen($phone) == 10 ? '1' : '') . $phone;

        $query = 'EXEC [addCustomer]';
        $query .= '@firstname = "' . trim($firstname) . '", ';
        $query .= '@lastname = "' . trim($lastname) . '", ';
        $query .= '@phone = "' . $phone . '", ';
        $query .= '@email = "' . trim($email) . '", ';
        $query .= '@addressId = "' . intval($addressId) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The address wasn\'t added.');
        }

        return new Customer(
            $rows[0]['id'],
            $firstname,
            $lastname,
            $phone,
            $email,
            $addressId);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getCustomerById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No customer found.');
        }
        return new Customer(
            $rows[0]['id'],
            $rows[0]['firstname'],
            $rows[0]['lastname'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']);
    }
}