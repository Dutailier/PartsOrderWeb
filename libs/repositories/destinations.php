<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/destination.php');

class Destinations
{
    public static function All()
    {
        $query = 'EXEC [getDestinations]';

        $rows = Database::Execute($query);

        $destinations = array();
        foreach ($rows as $row) {

            $destination = new Destination(
                $row['name']
            );
            $destination->setId($row['id']);

            $destinations[] = $destination;
        }
        return $destinations;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getDestinationById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No destination found.');
        }

        $destination = new Destination(
            $rows[0]['name']
        );
        $destination->setId($rows[0]['id']);

        return $destination;
    }
}