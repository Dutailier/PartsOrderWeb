<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/store.php');

class Stores
{
    public static function FindByUserId($id)
    {
        $query = 'EXEC [getRetailerByUserId]';
        $query .= '@userId = "' . intval($id) . '"';

        $rows = Database::Execute($query);


        if (empty($rows)) {
            throw new Exception('No retailer found.');
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

    public static function Find($id)
    {
        $query = 'EXEC [getRetailerById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No retailer found.');
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
}