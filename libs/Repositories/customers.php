<?php

include_once('config.php');
include(ROOT . 'libs/entities/customer.php');

class Customers
{
    public static function Add($firstname, $lastname, $phone, $email, $addressId)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertCustomer]("' .
                $addressId . '", "' .
                $firstname . '", "' .
                $lastname . '", "' .
                $phone . '", "' .
                $email . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Customer(
                    odbc_result($result, 'id'),
                    $firstname,
                    $lastname,
                    $phone,
                    $email,
                    $addressId);
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

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getCustomer]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Customer(
                    odbc_result($result, 'firstname'),
                    odbc_result($result, 'lastname'),
                    odbc_result($result, 'phone'),
                    odbc_result($result, 'email'));
            }
        }
    }
}