<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/order.php');

class Orders
{
    public static function Add($retailerId, $shippingAddressId, $customerId = null)
    {
        $query = 'EXEC [addOrder]';
        $query .= '@retailerId = "' . intval($retailerId) . '", ';
        $query .= '@shippingAddressId = "' . intval($shippingAddressId) . '"';

        if (!is_null($customerId)) {
            $query .= ', @customerId = "' . intval($customerId) . '"';
        }

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The order wasn\'t added.');
        }

        return new Order(
            $rows[0]['id'],
            $retailerId,
            $customerId,
            $shippingAddressId,
            $rows[0]['creationDate'],
            $rows[0]['deliveryDate'],
            $rows[0]['status']
        );
    }

    public static function Confirm($id)
    {
        $query = 'EXEC [confirmOrder]';
        $query .= '@id = "' . intval($id) . '"';

        Database::Execute($query);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getOrderById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No order found.');
        }

        return new Order(
            $rows[0]['id'],
            $rows[0]['retailerId'],
            $rows[0]['customerId'],
            $rows[0]['shippingAddressId'],
            $rows[0]['creationDate'],
            $rows[0]['deliveryDate'],
            $rows[0]['status']
        );
    }
}