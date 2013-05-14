<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/Part.php');


class Parts
{
    public static function FilterByCategoryId($id)
    {
        $query = 'EXEC [getPartsByCategoryId]';
        $query .= '@CategoryId = ' . $id;

        $rows = Database::Execute($query);

        $parts = array();
        foreach ($rows as $row) {
            $parts[] = new Part(
                $row['Id'],
                $row['Name'],
                $row['Description']);
        }
        return $parts;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getPartById]';
        $query .= '@Id = ' . $id;

        $row = Database::Execute($query);

        return new Part(
            $row['Id'],
            $row['Name'],
            $row['Description']);
    }
}