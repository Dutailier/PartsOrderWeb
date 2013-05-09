<?php

include_once('config.php');
include(ROOT . 'libs/entities/address.php');

class Addresses {
    public static function Add($details, $city, $zip, $stateId)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertAddress]("' .
                $details . '", "' .
                $city . '", "' .
                $zip . '", "' .
                $stateId . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Address(odbc_result($result, 'id'), $details, $city, $zip, $stateId);
            }
        }
    }

    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getAddress]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Address(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'details'),
                    odbc_result($result, 'city'),
                    odbc_result($result, 'zip'),
                    odbc_result($result, 'state_id'));
            }
        }
    }
}