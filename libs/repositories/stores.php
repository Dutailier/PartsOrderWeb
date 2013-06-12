<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/store.php');

class Stores
{
    public static function FilterByUserId($id)
    {
        $query = 'EXEC [getStoresByUserId]';
        $query .= '@userId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $stores = array();
        foreach ($rows as $row) {

            $store = new Store(
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

    public static function FilterByUsername($username)
    {
        $query = 'EXEC [getStoresByUsername]';
        $query .= '@username = "' . trim($username) . '"';

        $rows = Database::Execute($query);

        $stores = array();
        foreach ($rows as $row) {

            $store = new Store(
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
            $rows[0]['userId'],
            $rows[0]['name'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']);
        $store->setId($rows[0]['id']);

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