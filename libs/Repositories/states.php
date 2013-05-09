<?php
include_once('config.php');
include(ROOT . 'libs/entities/state.php');

class States
{
    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getState]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new State(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'name'),
                    odbc_result($result, 'country_id'));
            }
        }
    }

    public static function FilterByCountryId($id)
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
                while (odbc_fetch_row($result)) {
                    $states[] = new State(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name'),
                        $id);
                }

                return $states;
            }
        }
    }
}