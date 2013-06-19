<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/category.php');

class Categories
{
    public static function FilterByDestinationId($id)
    {
        $query = 'EXEC [getCategoriesByDestinationId]';
        $query .= '@destinationId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $categories = array();
        foreach ($rows as $row) {

            $category = new Category(
                $row['name']
            );
            $category->setId($row['id']);

            $categories[] = $category;
        }
        return $categories;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getCategoryById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No type found.');
        }

        $category = new Category(
            $rows[0]['name']
        );
        $category->setId($rows[0]['id']);

        return $category;
    }
}