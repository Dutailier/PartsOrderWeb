<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/product.php');

class Products
{
    public static function FilterByCategoryIdAndTypeId($categoryId, $typeId)
    {
        $query = 'EXEC [getProductsByCategoryIdAndTypeId]';
        $query .= '@categoryId = "' . intval($categoryId) . '", ';
        $query .= '@typeId = "' . intval($typeId) . '"';

        $rows = Database::Execute($query);

        $products = array();
        foreach ($rows as $row) {

            $product = new Product(
                $row['name'],
                $row['description']
            );
            $product->setId($row['id']);

            $products[] = $product;
        }
        return $products;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getProductById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No item found.');
        }

        $product = new Product(
            $rows[0]['name'],
            $rows[0]['description']
        );
        $product->setId($rows[0]['id']);

        return $product;
    }

    public static function getTypeIdBySerial($serial)
    {
        $query = 'EXEC [getTypeIdBySerial]';
        $query .= '@serial = "' . trim($serial) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('This serial doesn\'t exists.');
        }

        return $rows[0]['typeId'];
    }
}