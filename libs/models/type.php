<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

class Type
{
    public static function getTypes($category)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getTypes]("' . $category . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $types = array();
                $i = 0;
                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $types[$i]['id'] = odbc_result($result, 'id');
                    $types[$i]['name'] = odbc_result($result, 'name');
                    $types[$i]['description'] = odbc_result($result, 'description');
                    $i++;
                }

                return $types;
            }
        }
    }
}