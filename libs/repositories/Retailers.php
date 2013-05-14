<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/Retailer.php');

class Retailers
{
    public static function Find($id)
    {
        $query = 'EXEC [getRetailerById]';
        $query .= '@Id = ' . $id;

        $row = Database::Execute($query);

        return new Retailer(
            $row['Id'],
            $row['Name'],
            $row['Phone'],
            $row['Email'],
            $row['AddressId']
        );
    }

    public static function getConnected()
    {
        if (session_id() == '') {
            session_start();
        }

        if (empty($_SESSION['retailer'])) {
            $_SESSION['retailer'] = $_SESSION['user']->getId();
        }
        return $_SESSION['retailer'];
    }
}