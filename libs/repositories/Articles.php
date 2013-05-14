<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/Article.php');

class Articles
{
    public static function Add($orderId, $name, $description, $quantity)
    {
        $query = 'EXEC [insertPOArticle]';
        $query .= '@OrderId = ' . $orderId . ', ';
        $query .= '@Name = ' . $name . ', ';
        $query .= '@Descritpion = ' . $description . ', ';
        $query .= '@quantity = ' . $quantity;

        Database::Execute($query);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getPOArticleById]';
        $query .= '@Id = ' . $id;

        $row = Database::Execute($query);

        return new Articles(
            $row['OrderId'],
            $row['Name'],
            $row['Description'],
            $row['Quantity'],
            $row['Unit']
        );
    }
}