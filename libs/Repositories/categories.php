<?php

include_once('config.php');
include(ROOT . 'libs/entities/category.php');

class Categories
{
    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getCategory]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Category(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'name'));
            }
        }
    }

    public static function All()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCategories]}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $categories = array();
                while (odbc_fetch_row($result)) {
                    $categories[] = new Category(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name')
                    );
                }
                return $categories;
            }
        }
    }
}