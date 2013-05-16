<?php

include_once('config.php');
include_once(ROOT . 'libs/entities/retailer.php');

class Retailers
{
    public static function FindByUserId($id)
    {
        $query = 'EXEC [getRetailerByUserId]';
        $query .= '@userId = "' . intval($id) . '"';

        $rows = Database::Execute($query);


        if (empty($rows)) {
            throw new Exception('No retailer found.');
        }

        return new Retailer(
            $rows[0]['id'],
            $rows[0]['userId'],
            $rows[0]['name'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getRetailerById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No retailer found.');
        }
        return new Retailer(
            $rows[0]['id'],
            $rows[0]['userId'],
            $rows[0]['name'],
            $rows[0]['phone'],
            $rows[0]['email'],
            $rows[0]['addressId']);
    }
}