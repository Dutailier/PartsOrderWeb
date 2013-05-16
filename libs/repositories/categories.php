<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/category.php');

class Categories
{
    public static function All()
    {
        $query = 'EXEC [getCategories]';

        $rows = Database::Execute($query);

        $categories = array();
        foreach ($rows as $row) {
            $categories[] = new Category(
                $row['id'],
                $row['name'],
                $row['customerInfosAreRequired']
            );
        }

        return $categories;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getCategoryById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No cateogry found.');
        }

        return new Category(
            $rows[0]['id'],
            $rows[0]['name'],
            $rows[0]['customerInfosAreRequired']
        );
    }
}