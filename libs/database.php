<?php

/**
 * Class Database
 * Gère les méthodes d'accès à la base de données du projet.
 */
class Database
{
    /**
     * Retourne un tableau associatif des résultats de la requête SQL passée.
     * @param $query
     * @return array
     * @throws Exception
     */
    public static function Execute($query)
    {
        $conn = self::getConnection();

        $result = odbc_exec($conn, $query);

        // Définit le délai d'attente à 60 secondes plutôt que 30 secondes.
        odbc_setoption($result, 2, 0, 60);

        if (empty($result)) {
            echo $query;
            throw new Exception('The execution of the query failed.');
        }

        // On doit obligatoirement insérer ligne par ligne dans un tableau
        // chaque élément du résultat car les fonctions prédéfinies de ODBC
        // ne fonctionne pas correctement.
        $rows = array();
        while (odbc_fetch_row($result)) {

            $row = array();
            for ($i = 1; $i <= odbc_num_fields($result); $i++) {
                $column = odbc_field_name($result, $i);
                $row[$column] = odbc_result($result, $column);
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Retourne une connexion à la base de données.
     * @return resource
     * @throws Exception
     */
    private static function getConnection()
    {
        $conn = odbc_connect(
            'Driver={SQL SERVER}; Server=' . DB_HOST . '; Database=' . DB_NAME . ';',
            DB_USERNAME,
            DB_PASSWORD
        );

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        }

        return $conn;
    }
}
