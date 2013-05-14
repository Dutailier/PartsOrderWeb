<?php

include_once('config.php');
include_once(ROOT . 'libs/entities/Category.php');

class Categories
{
    public static function All()
    {
        $query = 'EXEC [getCategories]';

        $rows = Database::Execute($query);

        $categories = array();
        foreach ($rows as $row) {
            $categories[] = new Category(
                $row['Id'],
                $row['Name']);
        }

        return $categories;
    }
}