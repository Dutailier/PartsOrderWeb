<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/Order.php');


class Orders
{
    public static function Add($storeId, $customerId, $shipmentId)
    {
        $query = 'EXEC [insertPODetails]';
        $query .= '@StoreId = ' . $storeId . ', ';
        $query .= '@CustomerId = ' . $customerId . ', ';
        $query .= '@shipmentId = ' . $shipmentId;

        Database::Execute($query);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getPODetails]';
        $query .= '@Id = ' . $id;

        $row = Database::Execute($query);

        return new Order(
            $row['Id'],
            $row['StoreId'],
            $row['CustomerId'],
            $row['ShipmentId'],
            $row['CreationDate'],
            $row['DeliveryDate'],
            $row['Status']
        );
    }
}