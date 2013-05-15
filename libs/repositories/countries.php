<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/country.php');

class Countries
{
    public static function All()
    {
        $query = 'EXEC [getCountries]';

        $rows = Database::Execute($query);

        $countries = array();
        foreach ($rows as $row) {
            $countries[] = new Country($row['id'], $row['name']);
        }
        return $countries;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getCountryById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No country found.');
        }

        return new Country(
            $rows[0]['id'],
            $rows[0]['name']
        );
    }
}