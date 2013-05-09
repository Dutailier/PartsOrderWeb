<?php
include_once('config.php');
include(ROOT . 'libs/entities/type.php');

class Types
{
    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getType]("' . $id . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Type(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'name'),
                    odbc_result($result, 'description'));
            }
        }
    }

    public static function FilterByCategoryId($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {


            $sql = '{CALL [BruPartsOrderDb].[dbo].[getTypesByCategoryId]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $types = array();
                while (odbc_fetch_row($result)) {
                    $types[] = new Type(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name'),
                        odbc_result($result, 'description'));
                }

                return $types;
            }
        }
    }
}