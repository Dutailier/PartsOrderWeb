<?php

include_once('config.php');

class Database
{
    /**
     * Exécute une requête SQL.
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

        // On doit obligatoirement associer chaque champs à chaque
        // valeur du tableau car les fonctions prédéfinies d'ODBC
        // ne fonctionne pas.
        $rows = array();
        while (odbc_fetch_row($result)) {

            $row = array();
            for ($i = 1; $i <= odbc_num_fields($result); $i++) {
                $column = odbc_field_name($result, $i);
                $row[$column] = odbc_result($result, $column);
            }
            $rows[] = $row;
        }

        // S'il n'y a qu'une seule ligne, un simple tableau est retourné.
        // Autrement, un tableau de tableau (si plusieurs lignes) ou null
        // (si aucune ligne) est retourné.
        return count($rows) == 1 ? $rows[0] : $rows;
    }

    /**
     * Retourne une instance d'une connection ODBC.
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
