<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/filter.php');

class Filters
{
    public static function FilterByType($type)
    {
        $query = 'EXEC [getFiltersByType]';
        $query .= '@type = "' . trim($type) . '"';

        $rows = Database::Execute($query);

        $filters = array();
        foreach ($rows as $row) {

            $filter = new Filter(
                $row['name'],
                $row['type']
            );
            $filter->setId($row['id']);

            $filters[] = $filter;
        }
        return $filters;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getFilterById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No filter found.');
        }

        $filter = new Filter(
            $rows[0]['name'],
            $rows[0]['type']
        );
        $filter->setId($rows[0]['id']);

        return $filter;
    }
}