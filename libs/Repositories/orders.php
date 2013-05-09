<?php

include_once('config.php');
include(ROOT . 'libs/entities/order.php');

class Orders
{
    public static function Add($retailerId, $customerId = null)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            if (is_null($customerId)) {
                $sql = '{CALL [BruPartsOrderDb].[dbo].[placeOrderForRetailer]("' .
                    $retailerId . '")}';

            } else {
                $sql = '{CALL [BruPartsOrderDb].[dbo].[placeOrderForCustomer]("' .
                    $customerId . '", "' .
                    $retailerId . '")}';
            }

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Order(
                    odbc_result($result, 'id'),
                    $retailerId,
                    $customerId,
                    false);
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
            $sql = '{CALL [BruPartsOrderDb].[dbo].[getOrder]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Order(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'retailerId'),
                    odbc_result($result, 'customerId'),
                    odbc_result($result, 'isConfirmed'));
            }
        }
    }
}