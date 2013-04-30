<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

class Category
{
    public static function getCategories()
    {
        // Récupère la connexion à la base de données.
        $conn = database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCategories]}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $i = 0;
                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $categories[$i]['id'] = odbc_result($result, 'id');
                    $categories[$i]['name'] = odbc_result($result, 'name');
                    $i++;
                }

                return $categories;
            }
        }
    }
}