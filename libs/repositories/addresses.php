<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/address.php');

class Addresses
{
    public static function Add($details, $city, $zip, $stateId)
    {
        if (!preg_match(Address::REGEX_ZIP, $zip)) {
            throw new Exception('The zip code must be 5 digits.');
        }

        $query = 'EXEC [addAddress]';
        $query .= '@details = "' . trim($details) . '", ';
        $query .= '@city = "' . trim($city) . '", ';
        $query .= '@zip = "' . $zip . '", ';
        $query .= '@stateId = "' . intval($stateId) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            echo $query;
            throw new Exception('The address wasn\'t added.');
        }

        return new Address(
            $rows[0]['id'],
            $details,
            $city,
            $zip,
            $stateId);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getAddressById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No Address found.');
        }
        return new Address(
            $rows[0]['id'],
            $rows[0]['details'],
            $rows[0]['city'],
            $rows[0]['zip'],
            $rows[0]['stateId']);
    }
}