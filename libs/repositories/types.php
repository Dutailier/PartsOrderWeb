<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/type.php');

class Types
{
    public static function FilterByDestinationId($id)
    {
        $query = 'EXEC [getTypesByDestinationId]';
        $query .= '@destinationId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $types = array();
        foreach ($rows as $row) {

            $type = new Type(
                $row['name']
            );
            $type->setId($row['id']);

            $types[] = $type;
        }
        return $types;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getTypeById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No type found.');
        }

        $type = new Type(
            $rows[0]['name']
        );
        $type->setId($rows[0]['id']);

        return $type;
    }
}