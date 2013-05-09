<?php
include_once('config.php');
include(ROOT . 'libs/entities/part.php');

class Parts
{
    public static function FilterByOrderId($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {
            $sql = '{CALL [BruPartsOrderDb].[dbo].[getPartsByOrderId]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $parts = array();
                while (odbc_fetch_row($result)) {
                    $parts[] = new Part(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'serial_glider'),
                        odbc_result($result, 'type_id'),
                        odbc_result($result, 'sku'),
                        odbc_result($result, 'order_id'),
                        odbc_result($result, 'quantity'));
                }

                return $parts;
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
            $sql = '{CALL [BruPartsOrderDb].[dbo].[getPart]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Part(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'serial_glider'),
                    odbc_result($result, 'typeId'),
                    odbc_result($result, 'sku'));
            }
        }
    }

    public static function Add($typeId, $serialGlider, $quantity, $orderId)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertPart]("' .
                $typeId . '", "' .
                $serialGlider . '", "' .
                $orderId . '", "' .
                $quantity . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {
                odbc_fetch_row($result);
                return new Part(
                    odbc_result($result, 'id'),
                    $serialGlider,
                    $typeId,
                    null,
                    $quantity,
                    $orderId);
            }
        }
    }
}