<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/store.php');

class Stores
{
    public static function Attach(Store $store)
    {
        $query = 'EXEC [addStore]';
        $query .= '@bannerId = "' . $store->getBannerId() . '", ';
        $query .= '@userId = "' . $store->getUserId() . '", ';
        $query .= '@name = "' . $store->getName() . '", ';
        $query .= '@phone = "' . $store->getPhone() . '", ';
        $query .= '@email = "' . $store->getEmail() . '", ';
        $query .= '@addressId = "' . $store->getAddressId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The store wasn\'t added.');
        }

        $store->setId($rows[0]['id']);

        return $store;
    }

    public static function FilterByUserId($id)
    {
        $query = 'EXEC [getStoresByUserId]';
        $query .= '@userId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $stores = array();
        foreach ($rows as $row) {

            $store = new Store(
                $row['bannerId'],
                $row['userId'],
                $row['name'],
                $row['phone'],
                $row['email'],
                $row['addressId']);
            $store->setId($row['id']);

            $stores[] = $store;
        }
        return $stores;
    }

    public static function FilterByKeyWords($keyWords)
    {
        $query = 'EXEC [getStoresByKeyWords]';
        $query .= '@keyWords = "' . trim($keyWords) . '"';

        $rows = Database::Execute($query);

        $stores = array();
        foreach ($rows as $row) {

            $store = new Store(
                $row['bannerId'],
                $row['userId'],
                $row['name'],
                $row['phone'],
                $row['email'],
                $row['addressId']);
            $store->setId($row['id']);

            $stores[] = $store;
        }
        return $stores;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getStoreById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No store found.');
        }

        $store = new Store(
            $rows[0]['bannerId'],
            $rows[0]['userId'],
            $rows[0]['name'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']);
        $store->setId($rows[0]['id']);

        return $store;
    }

    public static function DeleteById($id)
    {
        $query = 'EXEC [deleteStoreById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No store deleted.');
        }
    }

    public static function Update(Store $store)
    {
        $query = 'EXEC [updateStore]';
        $query .= '@id = "' . $store->getId() . '", ';
        $query .= '@bannerId = "' . $store->getBannerId() . '", ';
        $query .= '@userId = "' . $store->getUserId() . '", ';
        $query .= '@name = "' . $store->getName() . '", ';
        $query .= '@phone = "' . $store->getPhone() . '", ';
        $query .= '@email = "' . $store->getEmail() . '", ';
        $query .= '@addressId = "' . $store->getAddressId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The store wasn\'t updated.');
        }

        return $store;
    }

    public static function FilterByBannerId($id)
    {
        $query = 'EXEC [getStoresByBannerId]';
        $query .= '@bannerId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $stores = array();
        foreach ($rows as $row) {

            $store = new Store(
                $row['bannerId'],
                $row['userId'],
                $row['name'],
                $row['phone'],
                $row['email'],
                $row['addressId']);
            $store->setId($row['id']);

            $stores[] = $store;
        }
        return $stores;
    }
}