<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/POAddress.php');

class POAddresses
{
    public static function Add($details, $city, $zip, $stateId)
    {
        $query = 'EXEC [insertPOAddress]';
        $query .= '@Details = ' . $details . ', ';
        $query .= '@City = ' . $city . ', ';
        $query .= '@Zip = ' . $zip . ', ';
        $query .= '@StateId = ' . $stateId;

        Database::Execute($query);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getPOAddressById]';
        $query .= '@Id = ' . $id;

        $row = Database::Execute($query);

        return new POAddresses(
            $row['Id'],
            $row['Details'],
            $row['City'],
            $row['Zip'],
            $row['StateId']
        );
    }
}