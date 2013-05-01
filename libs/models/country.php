<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

class Country
{
    /**
     * Retourne la liste des pays.
     * @return array
     * @throws Exception
     */
    public static function getCountries()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCountries]}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $countries = array();
                $i = 0;
                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $countries[$i]['id'] = odbc_result($result, 'id');
                    $countries[$i]['name'] = odbc_result($result, 'name');
                    $i++;
                }

                return $countries;
            }
        }
    }

    /**
     * Retourne la liste des états/provinces du pays.
     * @param $id
     * @return array
     * @throws Exception
     */
    public static function getStatesByCountryId($id)
    {

        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getStates]("' . $id . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $states = array();
                $i = 0;
                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $states[$i]['id'] = odbc_result($result, 'id');
                    $states[$i]['name'] = odbc_result($result, 'name');
                    $i++;
                }

                return $states;
            }
        }
    }
}