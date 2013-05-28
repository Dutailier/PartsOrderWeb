<?php

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

        if (empty($result)) {
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
     * Retourne l'instance d'une connection ODBC.
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
