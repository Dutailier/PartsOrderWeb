<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/part.php');

class Parts
{
    public static function FilterByCategoryId($id)
    {
        $query = 'EXEC [getPartsByCategoryId]';
        $query .= '@categoryId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $parts = array();
        foreach ($rows as $row) {
            $parts[] = new Part($row['id'], $row['name'], $row['description']);
        }
        return $parts;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getPartById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);


        if (empty($rows)) {
            throw new Exception('No part found.');
        }

        return new Part(
            $rows[0]['id'],
            $rows[0]['name'],
            $rows[0]['description']);
    }
}