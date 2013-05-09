<?php

include_once('config.php');
include(ROOT . 'libs/entities/country.php');

class Countries
{
    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getCountry]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Country(
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

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getCountries]}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $countries = array();
                while (odbc_fetch_row($result)) {
                    $countries[] = new Country(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name'));
                }

                return $countries;
            }
        }
    }
}