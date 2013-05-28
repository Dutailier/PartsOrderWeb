<?php

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

            $country = new Country(
                $row['name']);
            $country->setId($row['id']);

            $countries[] = $country;
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

        $country = new Country(
            $rows[0]['name']
        );
        $country->setId($rows[0]['id']);

        return $country;
    }
}