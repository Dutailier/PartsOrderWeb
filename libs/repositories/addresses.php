<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/address.php');

class Addresses
{
    public static function Attach(Address $address)
    {
        $query = 'EXEC [addAddress]';
        $query .= '@details = "' . $address->getDetails() . '", ';
        $query .= '@city = "' . $address->getCity() . '", ';
        $query .= '@zip = "' . $address->getZip() . '", ';
        $query .= '@stateId = "' . $address->getStateId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The address wasn\'t added.');
        }

        $address->setId($rows[0]['id']);

        return $address;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getAddressById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No Address found.');
        }

        $address = new Address(
            $rows[0]['details'],
            $rows[0]['city'],
            $rows[0]['zip'],
            $rows[0]['stateId']);
        $address->setId($rows[0]['id']);

        return $address;
    }
}