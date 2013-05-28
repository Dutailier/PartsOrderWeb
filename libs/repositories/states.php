<?php

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

            $state = new State(
                $row['name'],
                $row['countryId']
            );
            $state->setId($row['id']);

            $states[] = $state;
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

        $state = new State(
            $rows[0]['name'],
            $rows[0]['countryId']
        );
        $state->setId($rows[0]['id']);

        return $state;
    }
}