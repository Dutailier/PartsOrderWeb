<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/state.php');

class States
{
    public static function FilterByCountryId($id)
    {
        $query = 'EXEC [getStatesByCountryId]';
        $query .= '@countryId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $states = array();
        foreach ($rows as $row) {
            $states[] = new State($row['id'], $row['name'], $row['countryId']);
        }
        return $states;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getStateById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No state found.');
        }

        return new State(
            $rows[0]['id'],
            $rows[0]['name'],
            $rows[0]['countryId']);
    }
}