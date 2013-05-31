<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/product.php');

class Products
{
    public static function FilterByFilterIds(array $ids)
    {
        $list = $ids[0];
        for ($i = 1; $i < count($ids); $i++) {
            $list .= ',' . $ids[$i];
        }

        $query = 'EXEC [getProductsByFilterIds]';
        $query .= '@ids = "' . $list . '"';

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
}