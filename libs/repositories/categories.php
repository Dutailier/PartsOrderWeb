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
            $categories[] = new Category($row['id'], $row['name']);
        }

        return $categories;
    }
}